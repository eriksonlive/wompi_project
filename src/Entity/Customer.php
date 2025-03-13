<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    // formats: ['json' => ['application/json']],
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
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $taxnumber = null;

    #[ORM\Column(length: 255)]
    private ?string $customernumber = null;

    #[ORM\Column]
    private ?int $idtype = 31;

    #[ORM\Column(length: 255)]
    private ?string $address1 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?int $customer_id = null;

    #[ORM\OneToMany(mappedBy: "customer", targetEntity: Payments::class)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTaxnumber(): ?string
    {
        return $this->taxnumber;
    }

    public function setTaxnumber(?string $taxnumber): static
    {
        $this->taxnumber = $taxnumber;

        return $this;
    }

    public function getCustomernumber(): ?string
    {
        return $this->customernumber;
    }

    public function setCustomernumber(?string $customernumber): static
    {
        $this->customernumber = $customernumber;

        return $this;
    }

    public function getIdtype(): ?int
    {
        return $this->idtype;
    }

    public function setIdtype(?int $idtype): static
    {
        $this->idtype = $idtype;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
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

    public function setCustomerId(?int $customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }
}
