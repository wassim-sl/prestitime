<?php

namespace App\Entity;

use App\Repository\LigneDeCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneDeCommandeRepository::class)]
class LigneDeCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantité_article = null;

    #[ORM\Column]
    private ?float $prix_unitaire_article = null;

    #[ORM\ManyToOne(inversedBy: 'ligneDeCommandes')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantitéArticle(): ?int
    {
        return $this->quantité_article;
    }

    public function setQuantitéArticle(int $quantité_article): static
    {
        $this->quantité_article = $quantité_article;

        return $this;
    }

    public function getPrixUnitaireArticle(): ?float
    {
        return $this->prix_unitaire_article;
    }

    public function setPrixUnitaireArticle(float $prix_unitaire_article): static
    {
        $this->prix_unitaire_article = $prix_unitaire_article;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
