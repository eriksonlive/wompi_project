<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Payments;
use App\Repository\CustomerRepository;
use App\Repository\PaymentsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RoutesController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function fetchData(Request $request, PaymentsRepository $payments)
    {
        $paymentsData = $payments->findBy([], ['id' => 'DESC']);

        dump($paymentsData);

        return $this->render('index.html.twig', [
            'payments' => $paymentsData
        ]);
    }

    #[Route("/wompi", name: "wompi_transaction", methods: ["POST"])]
    public function handleWompi(Request $request, CustomerRepository $customers, EntityManagerInterface $em, HttpClientInterface $httpClient)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $expiresAt = new \DateTime();
        $expiresAt->modify('+12 hours'); // Sumar 12 horas
        $expiresAtFormatted = $expiresAt->format('Y-m-d\TH:i:s');

        $query = $em->createQuery("
            SELECT c FROM App\Entity\Customer c
            WHERE c.email = :email OR c.customernumber = :customerNumber
        ")->setParameter('email', $data['email'])
            ->setParameter('customerNumber', $data['document'])
            ->setMaxResults(1);

        $customer = $query->getOneOrNullResult();

        if (!$customer) {
            $customer = new Customer();
            $customer->setEmail($data["email"]);
            $customer->setName($data["name"]);
            $customer->setCustomerNumber($data["document"]);
            $customer->setIdtype($data['id_type'] ?? null);
            $customer->setCity($data['city'] ?? null);
            $customer->setAddress1($data['address'] ?? null);
            $customer->setPhone($data['phone'] ?? null);
            $em->persist($customer);
            $em->flush();
        }

        // 2️⃣ Crear una nueva transacción de pago en la BD
        $payment = new Payments();
        $payment->setCustomer($customer);
        $payment->setValor($data["valor"]);
        $payment->setEstado("PENDING");

        $em->persist($payment);
        $em->flush();

        // 3️⃣ Llamar a la API de Wompi para generar el botón de pago
        $wompiApiKey = $_ENV['WOMPI_KEY_DEV'] ?? '';  // ⚠️ Asegúrate de poner tu llave pública
        $response = $httpClient->request('POST', 'https://sandbox.wompi.co/v1/payment_links', [
            'headers' => ['Authorization' => 'Bearer ' . $wompiApiKey],
            'json' => [
                "name" => "Pago de " . $customer->getName(),
                "description" => "Compra en mi tienda",
                "currency" => "COP",
                "collect_shipping" => false,
                "amount_in_cents" => $data["valor"] * 100, // Wompi trabaja en centavos
                "single_use" => true,
                "expires_at" => $expiresAtFormatted
            ]
        ]);

        $wompiData = $response->toArray();

        if (!isset($wompiData['data']['id'])) {
            return new JsonResponse(['error' => 'No se pudo generar el enlace de pago'], 500);
        }

        $paymentUrl = "https://checkout.wompi.co/l/" . $wompiData['data']['id'];

        $vigencia = new \DateTime($wompiData['data']['expires_at']);
        $createAt = new \DateTime($wompiData['data']['created_at']);

        // 4️⃣ Actualizar la transacción con el ID del enlace de pago
        $payment->setIdReferencia($wompiData['data']['id']);
        $payment->setLink($paymentUrl);
        $payment->setVigenciaLink($vigencia);
        $payment->setFechaLink($createAt);
        $em->flush();

        return new JsonResponse([
            'message' => 'Pago generado con éxito',
            'payment_url' => $paymentUrl
        ], 200);
    }

    #[Route("/webhook/wompi", name: "wompi_webhook", methods: ["POST"])]
    public function handleWompiWebhook(Request $request, PaymentsRepository $payments, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener el contenido crudo del request (se espera que sea JSON)
        $content = $request->getContent();
        $data = json_decode($content, true);

        // Verifica que se haya recibido y decodificado el JSON correctamente
        if (!$data || !isset($data['data']['transaction'])) {
            return new JsonResponse(['error' => 'JSON inválido o estructura incorrecta'], 400);
        }

        // Extraer los datos necesarios
        $transaction = $data['data']['transaction'];
        $id = $transaction['id'] ?? null;
        $status = $transaction['status'] ?? null;
        $paymentLinkId = $transaction['payment_link_id'] ?? null;

        if (!$id || !$status || !$paymentLinkId) {
            return new JsonResponse(['error' => 'Datos incompletos en el webhook'], 400);
        }

        // Buscar el Payment en la base de datos usando el `idReferencia` (payment_link_id en el webhook)
        $payment = $payments->findOneBy(['idReferencia' => $paymentLinkId]);

        if ($payment) {
            // Actualizar solo los campos necesarios
            $payment->setEstado($status);
            $payment->setIdTransaccion($id);

            // Guardar cambios en la base de datos
            $entityManager->persist($payment);
            $entityManager->flush();

            return new JsonResponse(['status' => 'actualizado'], 200);
        } else {
            return new JsonResponse(['error' => 'Pago no encontrado'], 404);
        }
    }
}
