<?php

namespace App\Entity;

use App\Repository\QuotationDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationDetailRepository::class)]
class QuotationDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'quotationDetails')]
    private ?Quotation $quotation = null;

    #[ORM\ManyToOne(inversedBy: 'quotationDetails')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?float $tva = null;

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuotation(): ?Quotation
    {
        return $this->quotation;
    }

    public function setQuotation(?Quotation $quotation): static
    {
        $this->quotation = $quotation;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
