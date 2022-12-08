<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;


#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
#[ORM\Index(columns:['IdC'], name:'fk_idcat')]
#[ORM\Index(columns:['id'], name:'fk_iduser')]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['prixa' => 'exact'])]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: Categorieannonce::class,inversedBy: 'Annonces')]
    private ?int $idannonce = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'The email {{ value }} is not a valid email.'),]
    private ?string $titrea=null;

    #[ORM\Column(length: 500)]
    private ?string $descriptiona=null;

    #[ORM\Column]
    private ?float $prixa = null;

    #[ORM\Column]
    private ?string $datecreationa=null;

    #[ORM\Column]
    private ?string $imagea=null;

    #[ORM\JoinColumn(name: 'IdUser',referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private User $user;


    #[ORM\JoinColumn(name: 'IdC',referencedColumnName: 'idc')]
    #[ORM\ManyToOne(targetEntity: 'Categorieannonce')]
    private Categorieannonce $categorieannonce ;



    public function getIdannonce(): ?int
    {
        return $this->idannonce;
    }

    public function getTitrea(): ?string
    {
        return $this->titrea;
    }

    public function setTitrea(string $titrea): self
    {
        $this->titrea = $titrea;

        return $this;
    }

    public function getDescriptiona(): ?string
    {
        return $this->descriptiona;
    }

    public function setDescriptiona(string $descriptiona): self
    {
        $this->descriptiona = $descriptiona;

        return $this;
    }

    public function getPrixa(): ?float
    {
        return $this->prixa;
    }

    public function setPrixa(float $prixa): self
    {
        $this->prixa = $prixa;

        return $this;
    }

    public function getDatecreationa(): ?string
    {
        return $this->datecreationa;
    }

    public function setDatecreationa(string $datecreationa): self
    {
        $this->datecreationa = $datecreationa;

        return $this;
    }

    public function getImagea(): ?string
    {
        return $this->imagea;
    }

    public function setImagea(string $imagea): self
    {
        $this->imagea = $imagea;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */

    public function setUser(User $user): void
    {
        $this->user = $user;


    }

    /**
     * @return Categorieannonce
     */

    public function getCategorieannonce(): Categorieannonce
    {
        return $this->categorieannonce;
    }

    /**
     * @param Categorieannonce $categorieannonce
     */

    public function setCategorieannonce(Categorieannonce $categorieannonce): void
    {
        $this->categorieannonce=$categorieannonce;


    }


}
