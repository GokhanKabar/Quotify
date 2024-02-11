<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?User $userReference = null;

    #[ORM\OneToMany(mappedBy: 'quotation', targetEntity: QuotationDetail::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $quotationDetails;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $totalHT = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $totalTTC = null;

    public function getTotalHT(): ?float
    {
        return $this->totalHT;
    }

    public function setTotalHT(float $totalHT): static
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(float $totalTTC): static
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function __construct()
    {
        $this->quotationDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUserReference(): ?User
    {
        return $this->userReference;
    }

    public function setUserReference(?User $userReference): static
    {
        $this->userReference = $userReference;

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
            $quotationDetail->setQuotation($this);
        }

        return $this;
    }

    public function removeQuotationDetail(QuotationDetail $quotationDetail): static
    {
        if ($this->quotationDetails->removeElement($quotationDetail)) {
            // set the owning side to null (unless already changed)
            if ($quotationDetail->getQuotation() === $this) {
                $quotationDetail->setQuotation(null);
            }
        }

        return $this;
    }
}
