<?php

namespace App\Entity;

use App\Repository\SubstitutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstitutionRepository::class)]
class Substitution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $eanCodeToReplace = null;

    #[ORM\Column(length: 255)]
    private ?string $eanCodeOfSubstitute = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'substitutions')]
    private Collection $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brands = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $categories = [];

    #[ORM\Column(nullable: true)]
    private ?int $nutriscore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productName = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEanCodeToReplace(): ?string
    {
        return $this->eanCodeToReplace;
    }

    public function setEanCodeToReplace(string $eanCodeToReplace): self
    {
        $this->eanCodeToReplace = $eanCodeToReplace;

        return $this;
    }

    public function getEanCodeOfSubstitute(): ?string
    {
        return $this->eanCodeOfSubstitute;
    }

    public function setEanCodeOfSubstitute(string $eanCodeOfSubstitute): self
    {
        $this->eanCodeOfSubstitute = $eanCodeOfSubstitute;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getBrands(): ?string
    {
        return $this->brands;
    }

    public function setBrands(?string $brands): self
    {
        $this->brands = $brands;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getNutriscore(): ?int
    {
        return $this->nutriscore;
    }

    public function setNutriscore(?int $nutriscore): self
    {
        $this->nutriscore = $nutriscore;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }
}
