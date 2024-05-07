<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
#[ApiResource()]


class Etudiant extends User
{
    
    #[ORM\Column]
    private ?int $typeET = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="etudiants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

   

    public function getTypeET(): ?int
    {
        return $this->typeET;
    }

    public function setTypeET(int $typeET): static
    {
        $this->typeET = $typeET;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getIdClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setIdClasse(?Classe $idClasse): static
    {
        $this->classe = $idClasse;

        return $this;
    }
}