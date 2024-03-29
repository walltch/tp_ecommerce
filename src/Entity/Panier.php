<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column]
    private ?bool $etat = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\YourEntity", mappedBy="user")
     */
    private ?User $user = null;
    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    // Correction de la méthode setUser dans la classe Panier
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
