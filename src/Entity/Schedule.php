<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contentMorning = null;

    #[ORM\Column(length: 255)]
    private ?string $contentAfternoon = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    private ?Promo $promo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentMorning(): ?string
    {
        return $this->contentMorning;
    }

    public function setContentMorning(string $contentMorning): static
    {
        $this->contentMorning = $contentMorning;

        return $this;
    }

    public function getContentAfternoon(): ?string
    {
        return $this->contentAfternoon;
    }

    public function setContentAfternoon(string $contentAfternoon): static
    {
        $this->contentAfternoon = $contentAfternoon;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): static
    {
        $this->promo = $promo;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
