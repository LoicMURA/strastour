<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Ce nom d'utilisateur existe déjà !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user:game"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Votre nom d'utilisateur doit faire au minimum {{ limit }} caractères !",
     *     maxMessage="Votre nom d'utilisateur ne doit pas faire plus de {{ limit }} caractères !"
     * )
     * @Groups ({"user:game"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$#@%_])([-+!*$#@%_\w]{8,15})$/",
     *     message="Votre mot de passe doit comprendre au moins un chiffre, une minuscule, une
           majuscule et un caractère spécial (-+!*$#@%_)"
     * )
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *     propertyPath="password",
     *     message="La confirmation doit être simialaire au mot de passe"
     * )
     */
    private $passwordConfirm;

    /**
     * @Assert\Regex(
     *     pattern="/^[hHfF]$/"
     * )
     * @Groups ({"user:game"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"user:game"})
     */
    private $isPlayer;

    /**
     * @ORM\OneToMany(targetEntity=UserCourses::class, mappedBy="user")
     * @Groups ({"user:game"})
     */
    private $checkedCourses;

    /**
     * @ORM\OneToMany(targetEntity=UserPlaces::class, mappedBy="user")
     * @Groups ({"user:game"})
     */
    private $checkedPlaces;

    /**
     * @ORM\OneToOne(targetEntity=Character::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups ({"user:game"})
     */
    private $player;

    public function __construct()
    {
        $this->checkedCourses = new ArrayCollection();
        $this->checkedPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getIsPlayer(): ?bool
    {
        return $this->isPlayer;
    }

    public function setIsPlayer(bool $isPlayer): self
    {
        $this->isPlayer = $isPlayer;

        return $this;
    }

    public function getRoles(): ?array
    {
        return [$this->getRole()];
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    /**
     * @return Collection|UserCourses[]
     */
    public function getCheckedCourses(): Collection
    {
        return $this->checkedCourses;
    }

    public function addCheckedCourse(UserCourses $checkedCourse): self
    {
        if (!$this->checkedCourses->contains($checkedCourse)) {
            $this->checkedCourses[] = $checkedCourse;
            $checkedCourse->setUser($this);
        }

        return $this;
    }

    public function removeCheckedCourse(UserCourses $checkedCourse): self
    {
        if ($this->checkedCourses->removeElement($checkedCourse)) {
            // set the owning side to null (unless already changed)
            if ($checkedCourse->getUser() === $this) {
                $checkedCourse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserPlaces[]
     */
    public function getCheckedPlaces(): Collection
    {
        return $this->checkedPlaces;
    }

    public function addCheckedPlace(UserPlaces $checkedPlace): self
    {
        if (!$this->checkedPlaces->contains($checkedPlace)) {
            $this->checkedPlaces[] = $checkedPlace;
            $checkedPlace->setUser($this);
        }

        return $this;
    }

    public function removeCheckedPlace(UserPlaces $checkedPlace): self
    {
        if ($this->checkedPlaces->removeElement($checkedPlace)) {
            // set the owning side to null (unless already changed)
            if ($checkedPlace->getUser() === $this) {
                $checkedPlace->setUser(null);
            }
        }

        return $this;
    }

    public function getPlayer(): ?Character
    {
        return $this->player;
    }

    public function setPlayer(?Character $player): self
    {
        // unset the owning side of the relation if necessary
        if ($player === null && $this->player !== null) {
            $this->player->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($player !== null && $player->getUser() !== $this) {
            $player->setUser($this);
        }

        $this->player = $player;

        return $this;
    }
}
