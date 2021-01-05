<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagasinRepository::class)
 */
class Magasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siren;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity=StatistiqueMagasin::class, mappedBy="magasin", cascade={"persist", "remove"})
     */
    private $statistiqueMagasin;

    /**
     * @ORM\OneToOne(targetEntity=TypeMagasin::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeMagasin;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=Localisation::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $localisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getStatistiqueMagasin(): ?StatistiqueMagasin
    {
        return $this->statistiqueMagasin;
    }

    public function setStatistiqueMagasin(StatistiqueMagasin $statistiqueMagasin): self
    {
        // set the owning side of the relation if necessary
        if ($statistiqueMagasin->getMagasin() !== $this) {
            $statistiqueMagasin->setMagasin($this);
        }

        $this->statistiqueMagasin = $statistiqueMagasin;

        return $this;
    }

    public function getTypeMagasin(): ?TypeMagasin
    {
        return $this->typeMagasin;
    }

    public function setTypeMagasin(TypeMagasin $typeMagasin): self
    {
        $this->typeMagasin = $typeMagasin;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }
}
