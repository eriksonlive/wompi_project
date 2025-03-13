<?php

namespace App\Controller;

use App\Repository\PaymentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoutesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function fetchData(Request $request, PaymentsRepository $payments)
    {

        // $payment = $payments->findOneBy(['idReferencia' => 'test_gcom7x']);

        // dump($payment);

        return $this->render('index.html.twig');
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
