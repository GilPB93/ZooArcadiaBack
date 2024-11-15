<?php

namespace App\Entity;

use App\Repository\ZooRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZooRepository::class)]
class Zoo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $openning = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $closing = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenning(): ?\DateTimeInterface
    {
        return $this->openning;
    }

    public function setOpenning(\DateTimeInterface $openning): static
    {
        $this->openning = $openning;

        return $this;
    }

    public function getClosing(): ?\DateTimeInterface
    {
        return $this->closing;
    }

    public function setClosing(\DateTimeInterface $closing): static
    {
        $this->closing = $closing;

        return $this;
    }
}
