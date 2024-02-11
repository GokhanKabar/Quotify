<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $unitPrice = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: InvoiceDetail::class, cascade: ['persist', 'remove'])]
    private Collection $invoiceDetails;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: QuotationDetail::class, cascade: ['persist', 'remove'])]
    private Collection $quotationDetails;

    #[ORM\ManyToOne(inversedBy: 'products', cascade: ['persist', 'remove'])]
    private ?Company $companyReference = null;

    public function __construct()
    {
        $this->invoiceDetails = new ArrayCollection();
        $this->quotationDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

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

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceDetail>
     */
    public function getInvoiceDetails(): Collection
    {
        return $this->invoiceDetails;
    }

    public function addInvoiceDetail(InvoiceDetail $invoiceDetail): static
    {
        if (!$this->invoiceDetails->contains($invoiceDetail)) {
            $this->invoiceDetails->add($invoiceDetail);
            $invoiceDetail->setProduct($this);
        }

        return $this;
    }

    public function removeInvoiceDetail(InvoiceDetail $invoiceDetail): static
    {
        if ($this->invoiceDetails->removeElement($invoiceDetail)) {
            // set the owning side to null (unless already changed)
            if ($invoiceDetail->getProduct() === $this) {
                $invoiceDetail->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuotationDetail>
     */
    public function getQuotationDetails(): Collection
    {
        return $this->quotationDetails;
    }

    public function addQuotationDetail(QuotationDetail $quotationDetail): static
    {
        if (!$this->quotationDetails->contains($quotationDetail)) {
            $this->quotationDetails->add($quotationDetail);
            $quotationDetail->setProduct($this);
        }

        return $this;
    }

    public function removeQuotationDetail(QuotationDetail $quotationDetail): static
    {
        if ($this->quotationDetails->removeElement($quotationDetail)) {
            // set the owning side to null (unless already changed)
            if ($quotationDetail->getProduct() === $this) {
                $quotationDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getCompanyReference(): ?Company
    {
        return $this->companyReference;
    }

    public function setCompanyReference(?Company $companyReference): static
    {
        $this->companyReference = $companyReference;

        return $this;
    }
}
