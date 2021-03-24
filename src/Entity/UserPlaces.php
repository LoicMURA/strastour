<?php

namespace App\Entity;

use App\Repository\UserPlacesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserPlacesRepository::class)
 */
class UserPlaces
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Place::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_game"})
     */
    private $place;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="checkedPlaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\Column(type="boolean")
     * @Groups({"user_game"})
     */
    private $inRealLife;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

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

    public function getInRealLife(): ?bool
    {
        return $this->inRealLife;
    }

    public function setInRealLife(bool $inRealLife): self
    {
        $this->inRealLife = $inRealLife;

        return $this;
    }
}
