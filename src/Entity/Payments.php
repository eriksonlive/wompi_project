<?php

namespace App\Entity;

use App\Repository\PaymentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_referencia = null;

    #[ORM\Column(nullable: true)]
    private ?int $valor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pasarela = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $vigencia_link = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_link = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_organizacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $customer_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReferencia(): ?int
    {
        return $this->id_referencia;
    }

    public function setIdReferencia(?int $id_referencia): static
    {
        $this->id_referencia = $id_referencia;

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
        return $this->vigencia_link;
    }

    public function setVigenciaLink(?\DateTimeInterface $vigencia_link): static
    {
        $this->vigencia_link = $vigencia_link;

        return $this;
    }

    public function getFechaLink(): ?\DateTimeInterface
    {
        return $this->fecha_link;
    }

    public function setFechaLink(?\DateTimeInterface $fecha_link): static
    {
        $this->fecha_link = $fecha_link;

        return $this;
    }

    public function getIdOrganizacion(): ?int
    {
        return $this->id_organizacion;
    }

    public function setIdOrganizacion(?int $id_organizacion): static
    {
        $this->id_organizacion = $id_organizacion;

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
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(?int $customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }
}
