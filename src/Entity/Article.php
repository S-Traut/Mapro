<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $prix;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity=StatistiqueArticle::class, mappedBy="article", cascade={"persist", "remove"})
     */
    private $statistiqueArticle;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $magasin;

    /**
     * @ORM\ManyToOne(targetEntity=TypeArticle::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="article")
     */
    private $image;

    public function __construct()
    {
        $this->image = new ArrayCollection();
    }

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

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

    public function getStatistiqueArticle(): ?StatistiqueArticle
    {
        return $this->statistiqueArticle;
    }

    public function setStatistiqueArticle(StatistiqueArticle $statistiqueArticle): self
    {
        // set the owning side of the relation if necessary
        if ($statistiqueArticle->getArticle() !== $this) {
            $statistiqueArticle->setArticle($this);
        }

        $this->statistiqueArticle = $statistiqueArticle;

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): self
    {
        $this->magasin = $magasin;

        return $this;
    }

    public function getType(): ?TypeArticle
    {
        return $this->type;
    }

    public function setType(?TypeArticle $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(image $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }
}