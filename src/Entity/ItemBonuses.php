<?php

namespace App\Entity;

use App\Repository\ItemBonusesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemBonusesRepository::class)
 */
class ItemBonuses
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="itemBonuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Bonus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $bonus;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBonus(): ?Bonus
    {
        return $this->bonus;
    }

    public function setBonus(?Bonus $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
