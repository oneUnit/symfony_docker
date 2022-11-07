<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 4)]
    private $net_amount;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 4)]
    private $gross_amount;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 4)]
    private $tax_rate;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 4)]
    private $cost;

    #[ORM\OneToOne(mappedBy: 'sale_id', targetEntity: Contact::class, cascade: ['persist', 'remove'])]
    private $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNetAmount(): ?string
    {
        return $this->net_amount;
    }

    public function setNetAmount(string $net_amount): self
    {
        $this->net_amount = $net_amount;

        return $this;
    }

    public function getGrossAmount(): ?string
    {
        return $this->gross_amount;
    }

    public function setGrossAmount(string $gross_amount): self
    {
        $this->gross_amount = $gross_amount;

        return $this;
    }

    public function getTaxRate(): ?string
    {
        return $this->tax_rate;
    }

    public function setTaxRate(string $tax_rate): self
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        // unset the owning side of the relation if necessary
        if ($contact === null && $this->contact !== null) {
            $this->contact->setSaleId(null);
        }

        // set the owning side of the relation if necessary
        if ($contact !== null && $contact->getSaleId() !== $this) {
            $contact->setSaleId($this);
        }

        $this->contact = $contact;

        return $this;
    }


    public function getProfit(): string
    {
        $tax_amount = bcmul($this->getGrossAmount(), $this->getTaxRate(), 4);
        return bcsub(bcsub($this->getGrossAmount(), $tax_amount), $this->getCost(), 4);
    }

    public function getProfitPercent(): string
    {
        return bcdiv($this->getProfit(), $this->getGrossAmount(), 4);
    }

    public function jsonSerialize()
    {
        return array(
            'gross_amount' => $this->getGrossAmount(),
            'net_amount'=> $this->getNetAmount(),
            'tax_rate' => $this->getTaxRate(),
            'cost' => $this->getCost(),
            'profit' => $this->getProfit(),
            'profit_percent' => $this->getProfitPercent()
        );
    }
}
