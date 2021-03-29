<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1.json)
     * @Assert\Regex(
     *     pattern="/^[fFhH]{1.json,1.json}$/",
     *     message="Le genre doit être 'f', 'F', 'h' ou 'H'"
     * )
     * @Groups({"user_game"})
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message="La quantité d'xp doit être supérieur ou égal à 0"
     * )
     * @Groups({"user_game"})
     */
    private $xp;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message="Le nombre de stucks doit être supérieur ou égal à 0"
     * )
     * @Groups({"user_game"})
     */
    private $stuck;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_game"})
     */
    private $tutorialDone;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="player", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Inventory::class, mappedBy="player")
     * @Groups({"user_game"})
     */
    private $inventory;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getXp(): ?int
    {
        return $this->xp;
    }

    public function setXp(int $xp): self
    {
        $this->xp = $xp;

        return $this;
    }

    public function getStuck(): ?int
    {
        return $this->stuck;
    }

    public function setStuck(int $stuck): self
    {
        $this->stuck = $stuck;

        return $this;
    }

    public function getTutorialDone(): ?bool
    {
        return $this->tutorialDone;
    }

    public function setTutorialDone(bool $tutorialDone): self
    {
        $this->tutorialDone = $tutorialDone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Inventory[]
     */
    public function getInventory(): Collection
    {
        return $this->inventory;
    }

    public function addInventory(Inventory $inventory): self
    {
        if (!$this->inventory->contains($inventory)) {
            $this->inventory[] = $inventory;
            $inventory->setPlayer($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): self
    {
        if ($this->inventory->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getPlayer() === $this) {
                $inventory->setPlayer(null);
            }
        }

        return $this;
    }
}
