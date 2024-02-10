<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomService = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $disponibilite = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?categorie $categorie = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?commentaire $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?association $association = null;

    #[ORM\ManyToMany(targetEntity: Volontaire::class, mappedBy: 'condidature')]
    private Collection $volontaires;

    public function __construct()
    {
        $this->volontaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(string $nomService): static
    {
        $this->nomService = $nomService;

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

    public function isDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCommentaire(): ?commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?commentaire $commentaire): static
    {
        $this->commentaire = $commentaire;

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
     * @return Collection<int, Volontaire>
     */
    public function getVolontaires(): Collection
    {
        return $this->volontaires;
    }

    public function addVolontaire(Volontaire $volontaire): static
    {
        if (!$this->volontaires->contains($volontaire)) {
            $this->volontaires->add($volontaire);
            $volontaire->addCondidature($this);
        }

        return $this;
    }

    public function removeVolontaire(Volontaire $volontaire): static
    {
        if ($this->volontaires->removeElement($volontaire)) {
            $volontaire->removeCondidature($this);
        }

        return $this;
    }
}
