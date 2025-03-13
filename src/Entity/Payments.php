<?php

namespace App\Entity;

// use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PaymentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    order: ['id' => 'DESC'],
    collectionOperations: [
        'get' => ['method' => 'GET'],
        'post' => ['method' => 'POST']
    ],
    itemOperations: [
        'get' => ['method' => 'GET'], // Permite obtener un solo pago
        'patch' => ['method' => 'PATCH']
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['idReferencia' => 'exact'])]
#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "payments_id_seq", allocationSize: 1, initialValue: 1)]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idReferencia = null;

    #[ORM\Column]
    private ?int $valor = null;

    #[ORM\Column(length: 255, options: ["default" => "wompi"])]
    private ?string $pasarela = "wompi";

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $estado = 'IN PROCCESS';

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $vigenciaLink = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaLink = null;

    #[ORM\Column(nullable: true)]
    private ?int $idOrganizacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "payments")]
    #[ORM\JoinColumn(name: "customer", referencedColumnName: "id", nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idTransaccion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReferencia(): ?string
    {
        return $this->idReferencia;
    }

    public function setIdReferencia(?string $idReferencia): static
    {
        $this->idReferencia = $idReferencia;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(?int $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function getPasarela(): ?string
    {
        return $this->pasarela;
    }

    public function setPasarela(?string $pasarela): static
    {
        $this->pasarela = $pasarela;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getVigenciaLink(): ?\DateTimeInterface
    {
        return $this->vigenciaLink;
    }

    public function setVigenciaLink(?\DateTimeInterface $vigenciaLink): static
    {
        $this->vigenciaLink = $vigenciaLink;

        return $this;
    }

    public function getFechaLink(): ?\DateTimeInterface
    {
        return $this->fechaLink;
    }

    public function setFechaLink(?\DateTimeInterface $fechaLink): static
    {
        $this->fechaLink = $fechaLink;

        return $this;
    }

    public function getIdOrganizacion(): ?int
    {
        return $this->idOrganizacion;
    }

    public function setIdOrganizacion(?int $idOrganizacion): static
    {
        $this->idOrganizacion = $idOrganizacion;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getIdTransaccion(): ?string
    {
        return $this->idTransaccion;
    }

    public function setIdTransaccion(?string $idTransaccion): static
    {
        $this->idTransaccion = $idTransaccion;

        return $this;
    }
}
