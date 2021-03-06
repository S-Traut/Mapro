<?php

namespace App\Entity;

use App\Repository\FavoriMagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoriMagasinRepository::class)
 */
class FavoriMagasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="favoriMagasins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUtilisateur;

    /**
     * @ORM\Column(type="integer")
     */
    private $idMagasin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdMagasin(): ?int
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(int $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }
}
