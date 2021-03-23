<?php

namespace App\Entity;

use App\Repository\UserCoursesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserCoursesRepository::class)
 */
class UserCourses
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Course::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:game"})
     */
    private $course;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="checkedCourses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\Column(type="boolean")
     * @Groups({"user:game"})
     */
    private $inRealLife;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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
