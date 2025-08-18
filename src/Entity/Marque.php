<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'Cette marque existe déjà')]

class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Montre>
     */
    #[ORM\OneToMany(targetEntity: Montre::class, mappedBy: 'marque')]
    private Collection $montres;
     public function __toString():string
    {
        return $this->nom;
    }

    public function __construct()
    {
        $this->montres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Montre>
     */
    public function getMontres(): Collection
    {
        return $this->montres;
    }

    public function addMontre(Montre $montre): static
    {
        if (!$this->montres->contains($montre)) {
            $this->montres->add($montre);
            $montre->setMarque($this);
        }

        return $this;
    }

    public function removeMontre(Montre $montre): static
    {
        if ($this->montres->removeElement($montre)) {
            // set the owning side to null (unless already changed)
            if ($montre->getMarque() === $this) {
                $montre->setMarque(null);
            }
        }

        return $this;
    }
}
