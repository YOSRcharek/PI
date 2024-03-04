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
    #[Assert\NotBlank(message: "Le nom du membre ne doit pas être vide.")]
    #[Assert\Length(min:5, minMessage:"le Nom du membre doit faire au moins{{ limit }} caractéres")]
    private ?string $nomMembre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prenom du membre ne doit pas être vide.")]
    #[Assert\Length(min:5, minMessage:"le prenom du membre doit faire au moins{{ limit }} caractéres")]
    private ?string $prenomMembre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le numéro de téléphone du membre ne doit pas être vide.")]
    #[Assert\Regex(pattern: '/^\d+$/', message: "Le numéro de téléphone du membre doit contenir uniquement des chiffres.")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Le numéro de téléphone du membre doit avoir une longueur de 8 chiffres."
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email du membre ne doit pas être vide.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas une adresse email valide.")]
    private ?string $emailMembre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La fonction du membre ne doit pas être vide.")]
    #[Assert\Length(min:5, minMessage:"la fonction du membre doit faire au moins{{ limit }} caractéres")]
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
