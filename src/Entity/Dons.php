<?php

namespace App\Entity;

use App\Repository\DonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonsRepository::class)]
class Dons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateMisDon = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?TypeDons $type = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?association $association = null;

    #[ORM\ManyToMany(targetEntity: volontaire::class, inversedBy: 'dons')]
    private Collection $volontaire;

    public function __construct()
    {
        $this->volontaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateMisDon(): ?\DateTimeInterface
    {
        return $this->dateMisDon;
    }

    public function setDateMisDon(\DateTimeInterface $dateMisDon): static
    {
        $this->dateMisDon = $dateMisDon;

        return $this;
    }

    public function getType(): ?TypeDons
    {
        return $this->type;
    }

    public function setType(?TypeDons $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAssociation(): ?association
    {
        return $this->association;
    }

    public function setAssociation(?association $association): static
    {
        $this->association = $association;

        return $this;
    }

    /**
     * @return Collection<int, volontaire>
     */
    public function getVolontaire(): Collection
    {
        return $this->volontaire;
    }

    public function addVolontaire(volontaire $volontaire): static
    {
        if (!$this->volontaire->contains($volontaire)) {
            $this->volontaire->add($volontaire);
        }

        return $this;
    }

    public function removeVolontaire(volontaire $volontaire): static
    {
        $this->volontaire->removeElement($volontaire);

        return $this;
    }
}
