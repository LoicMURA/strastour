<?php

namespace App\Entity;

use App\Repository\BonusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BonusRepository::class)
 */
class Bonus
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
     *     min=3,
     *     max=255,
     *     minMessage="Le nom de votre bonus doit faire au minimum {{ limit }} caractères",
     *     maxMessage="Le nom de votre bonus doit faire au maximum {{ limit }} caractères"
     * )
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
