<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cet item existe déjà !"
 * )
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *     min=5,
     *     max=255,
     *     minMessage="Le nom de l'item doit faire au minimum {{ limit }} carctères",
     *     maxMessage="Le nom de l'item doit faire au maximum {{ limit }} carctères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *     @Assert\Length(
     *     min=10,
     *     max=750,
     *     minMessage="La description de l'item doit faire au minimum {{ limit }} carctères",
     *     maxMessage="La description de l'item doit faire au maximum {{ limit }} carctères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     *     mimeTypes={},
     *     mimeTypesMessage="L'image de l'item doit être un fichier .jpg ou .png",
     *     maxSize="200k",
     *     maxSizeMessage="L'image ne doit pas dépasser {{ limit }}Mo"
     * )
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(
     *     message="Le prix doit être supérieur à 0"
     * )
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=ItemBonuses::class, mappedBy="item")
     */
    private $itemBonuses;

    public function __construct()
    {
        $this->itemBonuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|ItemBonuses[]
     */
    public function getItemBonuses(): Collection
    {
        return $this->itemBonuses;
    }

    public function addItemBonus(ItemBonuses $itemBonus): self
    {
        if (!$this->itemBonuses->contains($itemBonus)) {
            $this->itemBonuses[] = $itemBonus;
            $itemBonus->setItem($this);
        }

        return $this;
    }

    public function removeItemBonus(ItemBonuses $itemBonus): self
    {
        if ($this->itemBonuses->removeElement($itemBonus)) {
            // set the owning side to null (unless already changed)
            if ($itemBonus->getItem() === $this) {
                $itemBonus->setItem(null);
            }
        }

        return $this;
    }
}
