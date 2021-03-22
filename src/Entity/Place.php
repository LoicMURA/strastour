<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce lieu existe déjà !"
 * )
 */
class Place
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
     *     minMessage="Le nom du lieu doit faire au minimum {{ limit }} caractères",
     *     maxMessage="Le nom du lieu doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min=10,
     *     minMessage="La description du parcours doit faire au minimum {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^(?=(\d{1,4})?[,]?[\'\ \wÀ-ÿ]+)([\w\d À-ÿ,\-']){15,}$/",
     *     message="L'adresse '{{ value }}' n'est pas valide"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     mimeTypesMessage="Votre fichier doit être une image au format .jpg ou .png",
     *     maxSize="350k",
     *     maxSizeMessage="Votre fichier ne doit pas dépasser {{ limit }}Mo"
     * )
     */
    private $picture;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *     min=-90,
     *     max=90,
     *     notInRangeMessage="La latitude du lieux doit être comprise entre {{ min }} et {{ max }}"
     * )
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *     min=-180,
     *     max=180,
     *     notInRangeMessage="La longitude du lieux doit être comprise entre {{ min }} et {{ max }}"
     * )
     */
    private $longitude;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}