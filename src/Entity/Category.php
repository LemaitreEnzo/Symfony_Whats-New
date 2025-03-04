<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Actu>
     */
    #[ORM\ManyToMany(targetEntity: Actu::class, mappedBy: 'category')]
    private Collection $actus;

    public function __construct()
    {
        $this->actus = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Actu>
     */
    public function getActus(): Collection
    {
        return $this->actus;
    }

    public function addActu(Actu $actu): static
    {
        if (!$this->actus->contains($actu)) {
            $this->actus->add($actu);
            $actu->setCategory($this);
        }

        return $this;
    }

    public function removeActu(Actu $actu): static
    {
        if ($actu->getCategory() === $this) {
            $actu->setCategory($this);
        }

        return $this;
    }
}
