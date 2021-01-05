<?php

namespace App\Entity;

use App\Repository\StatistiqueMagasinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatistiqueMagasinRepository::class)
 */
class StatistiqueMagasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbvue;

    /**
     * @ORM\OneToOne(targetEntity=Magasin::class, inversedBy="statistiqueMagasin", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $magasin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNbvue(): ?int
    {
        return $this->nbvue;
    }

    public function setNbvue(int $nbvue): self
    {
        $this->nbvue = $nbvue;

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(Magasin $magasin): self
    {
        $this->magasin = $magasin;

        return $this;
    }
}
