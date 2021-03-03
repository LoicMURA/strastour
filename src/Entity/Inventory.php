<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InventoryRepository::class)
 */
class Inventory
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="inventory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min=0,
     *     max=100,
     *     notInRangeMessage="La qualité de l'item doit être compris entre {{ min }} et {{ max }}"
     * )
     */
    private $quality;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Character
    {
        return $this->player;
    }

    public function setPlayer(?Character $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }
}
