<?php

namespace App\Service;                                        // Espace de noms de mon application (dossier src/Service)

use App\Entity\Montre;                                       // On va manipuler l'entité Montre (pour charger les infos depuis la BDD)
use Doctrine\ORM\EntityManagerInterface;                     // Pour accéder au repository (find par id)
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface; // Pour lire/écrire dans la session

class PanierService                                          // Déclaration de la classe de service
{
        private SessionInterface $session;                    // $session permet de stocker/récupérer des données persistantes par utilisateur
        private EntityManagerInterface $em;                   // $em permet de parler à la base de données (via les repositories)
        public function __construct(RequestStack $requestStack, EntityManagerInterface $em)  // Le constructeur : Symfony va injecter automatiquement ces dépendances
        {
            $this->session = $requestStack->getSession();//on récupére la session associée à l'utilisateur en cours puis on la stocke dans la variable privée $this    
            $this->em = $em; //stockage de l'EntityManager
        }

                                                          
    // --- ACCÈS À LA SESSION ---

    private function getPanier(): array                       // Méthode privée : lire le panier depuis la session
    {
        // On récupère la clé 'panier' dans la session. Si elle n'existe pas encore, on renvoie un tableau vide [].
        return $this->session->get('panier', []);
        // Format choisi : [ idMontre => quantite ]  ex: [ 12 => 1, 45 => 2 ]
    }

    private function savePanier(array $panier): void          // Méthode privée : écrire le panier en session
    {
        // On stocke le tableau $panier sous la clé 'panier' dans la session.
        $this->session->set('panier', $panier);
    }

    // --- ACTIONS SUR LE PANIER ---

    public function ajouter(int $idMontre): void              // Ajouter 1 unité d'une montre dans le panier
    {
        $panier = $this->getPanier();                         // On lit l'état actuel du panier en session
        $panier[$idMontre] = ($panier[$idMontre] ?? 0) + 1;   // Si l'id n'existe pas, on part de 0 ; puis on ajoute +1
        $this->savePanier($panier);                           // On sauvegarde le panier mis à jour dans la session
    }

    public function diminuer(int $idMontre): void             // Diminuer de 1 la quantité d'une montre
    {
        $panier = $this->getPanier();                         // On lit le panier
        if (!isset($panier[$idMontre])) return;               // Si l'id n'est pas présent, on ne fait rien

        $panier[$idMontre]--;                                 // On retire 1
        if ($panier[$idMontre] <= 0) {                        // Si la quantité tombe à 0 ou moins
            unset($panier[$idMontre]);                        // On supprime carrément l'entrée du panier
        }

        $this->savePanier($panier);                           // On sauvegarde le panier mis à jour
    }

    public function supprimer(int $idMontre): void            // Retirer complètement l'article du panier
    {
        $panier = $this->getPanier();                         // On lit le panier
        unset($panier[$idMontre]);                            // On enlève la ligne correspondant à cette montre
        $this->savePanier($panier);                           // On sauvegarde
    }

    // --- CONSTRUCTION DES LIGNES POUR L'AFFICHAGE ---

    
    public function getArticles(): array                      // Transformer [id => qte] en objets Montre + quantité
    {
        $items = [];                                          // On prépare un tableau résultat
        $repo = $this->em->getRepository(Montre::class);      // On récupère le repository de l'entité Montre

        foreach ($this->getPanier() as $id => $quantite) {    // Pour chaque ligne du panier (id => qte)
            $montre = $repo->find($id);                       // On charge l'objet Montre depuis la BDD
            if ($montre) {                                    // Si la montre existe bien (sécurité)
                $items[] = [                                  // On ajoute une ligne structurée pour l'affichage
                    'montre'   => $montre,                    // L'objet Montre complet (nom, marque, prix, etc.)
                    'quantite' => $quantite,                  // La quantité choisie par l'utilisateur
                ];
            }
        }

        return $items;                                        // On renvoie la liste des items prêts pour Twig
    }

    // --- CALCULS DES TOTAUX ---

    public function getSousTotal(): float                     // Calcule la somme des (prix * quantité) sans les frais
    {
        // On additionne, pour chaque item, le prix de la montre multiplié par sa quantité
        return array_reduce(
            $this->getArticles(),             // On parcourt les items construits ci-dessus
            function (float $somme, array $item): float {     // $item = ['montre' => Montre, 'quantite' => int]
                return $somme + ($item['montre']->getPrix()   // On lit le prix depuis l'entité (sécurisé côté serveur)
                    * $item['quantite']);        // Multiplié par la quantité
            },
            0.0                                               // Valeur initiale de la somme
        );
    }
    
    public function getFraisLivraison(): float                // Renvoie les frais de livraison (ici fixés à 0)
    {
        return 0.0;                                           
    }

    public function getTotal(): float                         // Calcule le total final
    {
        return $this->getSousTotal() + $this->getFraisLivraison(); // Total = sous-total + livraison
    }
}
