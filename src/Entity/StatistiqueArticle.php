<?php

namespace App\Entity;

use App\Repository\StatistiqueArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatistiqueArticleRepository::class)
 */
class StatistiqueArticle
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
     * @ORM\OneToOne(targetEntity=Article::class, inversedBy="statistiqueArticle", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
