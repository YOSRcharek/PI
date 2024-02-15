<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomMembre = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomMembre = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $emailMembre = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    private Association $association;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMembre(): ?string
    {
        return $this->nomMembre;
    }

    public function setNomMembre(string $nomMembre): static
    {
        $this->nomMembre = $nomMembre;

        return $this;
    }

    public function getPrenomMembre(): ?string
    {
        return $this->prenomMembre;
    }

    public function setPrenomMembre(string $prenomMembre): static
    {
        $this->prenomMembre = $prenomMembre;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmailMembre(): ?string
    {
        return $this->emailMembre;
    }

    public function setEmailMembre(string $emailMembre): static
    {
        $this->emailMembre = $emailMembre;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getAssociation(): ?association
    {
        return $this->association;
    }

    public function setAssociation(Association $association): static
    {
        $this->association = $association;

        return $this;
    }
}
