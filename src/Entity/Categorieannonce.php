<?php

namespace App\Entity;

use App\Repository\CategorieannonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieannonceRepository::class)]
class Categorieannonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\OneToMany(targetEntity: 'Annonce')]
    private ?int $idc = null;

    #[ORM\Column(length: 20)]
    private ?string $nomcat = null;

    #[ORM\Column(length: 500)]
    private ?string $descriptioncat = null;

    public function getIdc(): ?int
    {
        return $this->idc;
    }
    public function __ToString(){
        return $this->nomcat;
    }

    public function getNomcat(): ?string
    {
        return $this->nomcat;
    }

    public function setNomcat(string $nomcat): self
    {
        $this->nomcat = $nomcat;

        return $this;
    }

    public function getDescriptioncat(): ?string
    {
        return $this->descriptioncat;
    }

    public function setDescriptioncat(string $descriptioncat): self
    {
        $this->descriptioncat = $descriptioncat;

        return $this;
    }



}
