<?php

namespace App\Entity;

use App\Repository\RapportVetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportVetRepository::class)]
class RapportVet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $etatSante = null;

    #[ORM\Column(length: 124)]
    private ?string $nourritureSuggeree = null;

    #[ORM\Column(length: 64)]
    private ?string $quantiteSuggeree = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentHabitat = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtatSante(): ?string
    {
        return $this->etatSante;
    }

    public function setEtatSante(string $etatSante): static
    {
        $this->etatSante = $etatSante;

        return $this;
    }

    public function getNourritureSuggeree(): ?string
    {
        return $this->nourritureSuggeree;
    }

    public function setNourritureSuggeree(string $nourritureSuggeree): static
    {
        $this->nourritureSuggeree = $nourritureSuggeree;

        return $this;
    }

    public function getQuantiteSuggeree(): ?string
    {
        return $this->quantiteSuggeree;
    }

    public function setQuantiteSuggeree(string $quantiteSuggeree): static
    {
        $this->quantiteSuggeree = $quantiteSuggeree;

        return $this;
    }

    public function getCommentHabitat(): ?string
    {
        return $this->commentHabitat;
    }

    public function setCommentHabitat(string $commentHabitat): static
    {
        $this->commentHabitat = $commentHabitat;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
