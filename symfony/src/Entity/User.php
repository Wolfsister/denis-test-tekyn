<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: FavoriteProduct::class, mappedBy: 'User', cascade: ["persist"])]
    private Collection $favoriteProducts;

    #[ORM\ManyToMany(targetEntity: Substitution::class, mappedBy: 'user', cascade: ["persist"])]
    private Collection $substitutions;

    #[ORM\ManyToMany(targetEntity: ExcludedProduct::class, mappedBy: 'user', cascade: ["persist"])]
    private Collection $excludedProducts;

    public function __construct()
    {
        $this->favoriteProducts = new ArrayCollection();
        $this->substitutions = new ArrayCollection();
        $this->excludedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, FavoriteProduct>
     */
    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if (!$this->favoriteProducts->exists(function($key, $existingProduct) use ($favoriteProduct) {
            return $favoriteProduct->getCode() === $existingProduct->getCode();
        })) {
            $this->favoriteProducts->add($favoriteProduct);
            $favoriteProduct->addUser($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            $favoriteProduct->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Substitution>
     */
    public function getSubstitutions(): Collection
    {
        return $this->substitutions;
    }

    public function addSubstitution(Substitution $substitution): self
    {
        if (!$this->substitutions->exists(function($key, $existingSubstitution) use ($substitution) {
            return $existingSubstitution->getEanCodeToReplace() === $substitution->getEanCodeToReplace();
        })) {
            $this->substitutions->add($substitution);
            $substitution->addUser($this);
        }

        return $this;
    }

    public function removeSubstitution(Substitution $substitution): self
    {
        if ($this->substitutions->removeElement($substitution)) {
            $substitution->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ExcludedProduct>
     */
    public function getExcludedProducts(): Collection
    {
        return $this->excludedProducts;
    }

    public function addExcludedProduct(ExcludedProduct $excludedProduct): self
    {
        if (!$this->excludedProducts->exists(function($key, $existingExclusion) use ($excludedProduct) {
            return $existingExclusion->getCode() === $excludedProduct->getCode();
        })) {
            $this->excludedProducts->add($excludedProduct);
            $excludedProduct->addUser($this);
        }

        return $this;
    }

    public function removeExcludedProduct(ExcludedProduct $excludedProduct): self
    {
        if ($this->excludedProducts->removeElement($excludedProduct)) {
            $excludedProduct->removeUser($this);
        }

        return $this;
    }
}
