<?php

namespace App\Entity;

use App\Repository\SellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;


#[ORM\Entity(repositoryClass: SellerRepository::class)]
class Seller implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'datetime')]
    private $date_joined;

    #[ORM\OneToMany(mappedBy: 'seller_id', targetEntity: Contact::class)]
    private $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getDateJoined(): ?\DateTimeInterface
    {
        return $this->date_joined;
    }

    public function setDateJoined(\DateTimeInterface $date_joined): self
    {
        $this->date_joined = $date_joined;

        return $this;
    }

    

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setSellerId($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getSellerId() === $this) {
                $contact->setSellerId(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'first_name' => $this->getFirstName(),
            'last_name'=> $this->getLastName(),
            'date_joined' => $this->getDateJoined()->format('Y-m-d')
        );
    }
}
