<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'événement ne doit pas être vide.")]
    #[Assert\Length(min:5,minMessage:"le Nom d'evenement doit faire au moins{{ limit }} caractéres")]
    private ?string $nomEvent = null;   

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\Image(
     mimeTypes: ["image/jpeg", "image/png"],
        mimeTypesMessage: "Veuillez télécharger une image JPEG ou PNG valide."
    )]
    private ?string $image = null;

   
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début ne doit pas être vide.")]
    private ?\DateTimeInterface $dateDebut = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
        #[Assert\NotBlank(message: "La date de fin ne doit pas être vide.")]
    #[Assert\GreaterThan(propertyPath: "dateDebut", message: "La date de fin doit être postérieure à la date de début.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La localisation ne doit pas être vide.")]
    private ?string $localisation = null;
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank(message: "La capacité maximale ne doit pas être vide.")]
   #[Assert\GreaterThanOrEqual(propertyPath: "capaciteActuelle", message: "La capacité maximale doit être supérieure ou égale à la capacité actuelle.")]
   #[Assert\GreaterThanOrEqual(value: 0, message: "La capacité maximale ne doit pas être négative.")]
   private ?int $capaciteMax = null;

   #[ORM\Column(type: Types::INTEGER)]
   #[Assert\NotBlank(message: "La capacité actuelle ne doit pas être vide.")]
   #[Assert\GreaterThanOrEqual(value: 0, message: "La capacité actuelle ne doit pas être négative.")]
   private ?int $capaciteActuelle = 0;

    #[ORM\ManyToOne(inversedBy: 'events', cascade: ['remove'])]
    private ?TypeEvent $type = null;

    #[ORM\ManyToMany(targetEntity: Volontaire::class, mappedBy: 'event')]
    private Collection $volontaires;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Association $association = null;

    public function __construct()
    {
        $this->volontaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }


    public function setNomEvent(string $nomEvent): static
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }
    public function getImage(): ?string
{
    return $this->image;
}

public function setImage(?string $image): self
{
    $this->image = $image;

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCapaciteMax(): ?int
    {
        return $this->capaciteMax;
    }

    public function setCapaciteMax(int $capaciteMax): static
    {
        $this->capaciteMax = $capaciteMax;

        return $this;
    }

    public function getCapaciteActuelle(): ?int
    {
        return $this->capaciteActuelle;
    }

    public function setCapaciteActuelle(int $capaciteActuelle): static
    {
        $this->capaciteActuelle = $capaciteActuelle;

        return $this;
    }

    public function getType(): ?TypeEvent
    {
        return $this->type;
    }

    public function setType(?TypeEvent $type): static
    {
        $this->type = $type;

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
            $volontaire->addEvent($this);
        }

        return $this;
    }
    public function participer(): void
    {
        $this->capaciteActuelle += 1;
    }

    public function removeVolontaire(Volontaire $volontaire): static
    {
        if ($this->volontaires->removeElement($volontaire)) {
            $volontaire->removeEvent($this);
        }

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): static
    {
        $this->association = $association;

        return $this;
    }
}
