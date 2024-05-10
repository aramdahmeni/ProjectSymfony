<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AdministrateurRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\AdminType;
#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
#[ApiResource()]
class Administrateur extends User
{
/**
 * @ORM\Column(length: 255)
 * @Assert\Choice(choices=AdminType::values(), message="Choose a valid type.")
 */
    private ?string $role = null;


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}