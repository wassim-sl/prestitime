<?php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier_index')]
    public function index(PanierService $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => [
                'articles'   => $panier->getArticles(),
                'sous_total' => $panier->getSousTotal(),
                'total'      => $panier->getTotal(),
            ],
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'panier_ajouter')]
    public function ajouter(int $id, PanierService $panier): Response
    {
        $panier->ajouter($id);
        return $this->redirectToRoute('app_panier_index');
                $this->addFlash('success', 'produit ajouté au panier.');

    }

    #[Route('/panier/diminuer/{id}', name: 'panier_diminuer')]
    public function diminuer(int $id, PanierService $panier): Response
    {
        $panier->diminuer($id);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer')]
    public function supprimer(int $id, PanierService $panier): Response
    {
        $panier->supprimer($id);
       

        return $this->redirectToRoute('app_panier_index');
         $this->addFlash('success', 'Produit a bien été supprimé.');
    }
}
