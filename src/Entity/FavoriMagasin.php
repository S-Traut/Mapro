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
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favoriMagasins")
     */
    private $idUtilisateur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idMagasin;

    public function __construct()
    {
        $this->idUtilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getIdUtilisateur(): Collection
    {
        return $this->idUtilisateur;
    }

    public function addIdUtilisateur(User $idUtilisateur): self
    {
        if (!$this->idUtilisateur->contains($idUtilisateur)) {
            $this->idUtilisateur[] = $idUtilisateur;
        }

        return $this;
    }

    public function removeIdUtilisateur(User $idUtilisateur): self
    {
        $this->idUtilisateur->removeElement($idUtilisateur);

        return $this;
    }

    public function getIdMagasin(): ?int
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(?int $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }
}
