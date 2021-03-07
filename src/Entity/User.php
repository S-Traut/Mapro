<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Localisation::class, mappedBy="user")
     */
    private $localisation;

    /**
     * @ORM\OneToMany(targetEntity=Magasin::class, mappedBy="idUtilisateur")
     */
    private $magasins;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=FavoriMagasin::class, mappedBy="idUtilisateur", orphanRemoval=true)
     */
    private $favoriMagasins;

    /**
     * @ORM\OneToMany(targetEntity=FavoriArticle::class, mappedBy="idUtilisateur", orphanRemoval=true)
     */
    private $favoriArticles;

    public function __construct()
    {
        $this->favoriMagasins = new ArrayCollection();
        $this->favoriArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    /**
     * @return Collection|Localisation[]
     */
    public function getLocalisation(): Collection
    {
        return $this->localisation;
    }

    public function addLocalisation(Localisation $localisation): self
    {
        if (!$this->localisation->contains($localisation)) {
            $this->localisation[] = $localisation;
            $localisation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): self
    {
        if ($this->localisation->removeElement($localisation)) {
            // set the owning side to null (unless already changed)
            if ($localisation->getUtilisateur() === $this) {
                $localisation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Magasin[]
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): self
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins[] = $magasin;
            $magasin->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): self
    {
        if ($this->magasins->removeElement($magasin)) {
            // set the owning side to null (unless already changed)
            if ($magasin->getIdUtilisateur() === $this) {
                $magasin->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|FavoriMagasin[]
     */
    public function getFavoriMagasins(): Collection
    {
        return $this->favoriMagasins;
    }

    public function addFavoriMagasin(FavoriMagasin $favoriMagasin): self
    {
        if (!$this->favoriMagasins->contains($favoriMagasin)) {
            $this->favoriMagasins[] = $favoriMagasin;
            $favoriMagasin->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeFavoriMagasin(FavoriMagasin $favoriMagasin): self
    {
        if ($this->favoriMagasins->removeElement($favoriMagasin)) {
            // set the owning side to null (unless already changed)
            if ($favoriMagasin->getIdUtilisateur() === $this) {
                $favoriMagasin->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavoriArticle[]
     */
    public function getFavoriArticles(): Collection
    {
        return $this->favoriArticles;
    }

    public function addFavoriArticle(FavoriArticle $favoriArticle): self
    {
        if (!$this->favoriArticles->contains($favoriArticle)) {
            $this->favoriArticles[] = $favoriArticle;
            $favoriArticle->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeFavoriArticle(FavoriArticle $favoriArticle): self
    {
        if ($this->favoriArticles->removeElement($favoriArticle)) {
            // set the owning side to null (unless already changed)
            if ($favoriArticle->getIdUtilisateur() === $this) {
                $favoriArticle->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
