<?php

namespace App\Entity;

use App\Repository\VolontaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolontaireRepository::class)]
class Volontaire extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(type: Types::BLOB)]
    private $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'volontaires')]
    private Collection $event;

    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'volontaires')]
    private Collection $condidature;

    #[ORM\ManyToMany(targetEntity: Dons::class, mappedBy: 'volontaire')]
    private Collection $dons;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->condidature = new ArrayCollection();
        $this->dons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

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

    /**
     * @return Collection<int, event>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(event $event): static
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
        }

        return $this;
    }

    public function removeEvent(event $event): static
    {
        $this->event->removeElement($event);

        return $this;
    }

    /**
     * @return Collection<int, service>
     */
    public function getCondidature(): Collection
    {
        return $this->condidature;
    }

    public function addCondidature(service $condidature): static
    {
        if (!$this->condidature->contains($condidature)) {
            $this->condidature->add($condidature);
        }

        return $this;
    }

    public function removeCondidature(service $condidature): static
    {
        $this->condidature->removeElement($condidature);

        return $this;
    }

    /**
     * @return Collection<int, Dons>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Dons $don): static
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->addVolontaire($this);
        }

        return $this;
    }

    public function removeDon(Dons $don): static
    {
        if ($this->dons->removeElement($don)) {
            $don->removeVolontaire($this);
        }

        return $this;
    }
}
