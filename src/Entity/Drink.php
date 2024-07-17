<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(),
        new Post(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or object == user", securityMessage: 'You are not allowed to get this order'),
        new Get(),
        new Put(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or object == user", securityMessage: 'You are not allowed to edit this order'),
        new Delete(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or object == user", securityMessage: 'You are not allowed to delete this order'),
    ],
    forceEager: false
)]
#[ORM\Entity(repositoryClass: DrinkRepository::class)]
class Drink
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column]
    private ?int $price = null;

    #[Groups(['read', 'write'])]
    #[ORM\OneToOne(inversedBy: 'drink', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $picture = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(Media $picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
