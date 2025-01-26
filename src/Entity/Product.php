<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['product:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product:read'])]
    private ?string $brand = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product:read'])]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $unit_price_tax_incl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $unit_price_tax_excl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUnitPriceTaxIncl(): ?string
    {
        return $this->unit_price_tax_incl;
    }

    public function setUnitPriceTaxIncl(string $unit_price_tax_incl): static
    {
        $this->unit_price_tax_incl = $unit_price_tax_incl;

        return $this;
    }

    public function getUnitPriceTaxExcl(): ?string
    {
        return $this->unit_price_tax_excl;
    }

    public function setUnitPriceTaxExcl(string $unit_price_tax_excl): static
    {
        $this->unit_price_tax_excl = $unit_price_tax_excl;

        return $this;
    }
}
