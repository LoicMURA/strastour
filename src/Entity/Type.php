<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce type d'item existe déjà !"
 * )
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"course:show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *     min=5,
     *     max=255,
     *     minMessage="Le nom du type d'item doit faire au minimum {{ limit }} caractères",
     *     maxMessage="Le nom du type d'item doit faire au maximum {{ limit }} caractères"
     * )
     * @Groups({"course:show"})
     */
    private $name;

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
}
