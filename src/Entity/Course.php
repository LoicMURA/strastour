<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette course existe déjà !"
 * )
 */
class Course
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
     *     minMessage="Le nom du parcours doit faire au minimum {{ limit }} caractères",
     *     maxMessage="Le nom du parcours doit faire au maximum {{ limit }} caractères"
     * )
     * @Groups({"course:show"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min=10,
     *     minMessage="La description du parcours doit faire au minimum {{ limit }} caractères"
     * )
     * @Groups({"course:show"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     mimeTypesMessage="Votre fichier doit être une image au format .jpg ou .png",
     *     maxSize="350k",
     *     maxSizeMessage="Votre fichier ne doit pas dépasser {{ limit }}Mo"
     * )
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"course:show"})
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"course:show"})
     */
    private $item;

    /**
     * @ORM\OneToMany(targetEntity=CoursePlace::class, mappedBy="course")
     * @Groups({"course:show"})
     */
    private $places;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="course")
     */
    private $comments;

    public function __construct()
    {
        $this->places = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

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

    /**
     * @return Collection|CoursePlace[]
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(CoursePlace $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
            $place->setCourse($this);
        }

        return $this;
    }

    public function removePlace(CoursePlace $place): self
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getCourse() === $this) {
                $place->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCourse($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCourse() === $this) {
                $comment->setCourse(null);
            }
        }

        return $this;
    }
}
