<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use JsonSerializable;


#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Seller::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private $seller_id;

    #[ORM\ManyToOne(inversedBy: 'contact', targetEntity: Region::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $region_id;

    #[ORM\OneToOne(inversedBy: 'contact', targetEntity: Sale::class, cascade: ['persist', 'remove'])]
    private $sale_id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $customer_full_name;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private $product_id;

    

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $uuid): self
    {
        $this->id = $uuid;

        return $this;
    }

    public function getSellerId(): ?Seller
    {
        return $this->seller_id;
    }

    public function setSellerId(?Seller $seller_id): self
    {
        $this->seller_id = $seller_id;

        return $this;
    }

    public function getRegionId(): ?Region
    {
        return $this->region_id;
    }

    public function setRegionId(?Region $region_id): self
    {
        $this->region_id = $region_id;

        return $this;
    }

    public function getSaleId(): ?Sale
    {
        return $this->sale_id;
    }

    public function setSaleId(?Sale $sale_id): self
    {
        $this->sale_id = $sale_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCustomerFullName(): ?string
    {
        return $this->customer_full_name;
    }

    public function setCustomerFullName(string $customer_full_name): self
    {
        $this->customer_full_name = $customer_full_name;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(?Product $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'type' => $this->getType(),
            'customer_full_name'=> $this->getCustomerFullName(),
            'product' => $this->getProductId(),
            'sale' => $this->getSaleId(),
            'region' => $this->getRegionId(),
            'seller' => $this->getSellerId()
        );
    }
}
