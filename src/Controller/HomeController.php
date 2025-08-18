<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Repository\MontreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MontreRepository $repo): Response
    {
        $montres = $repo->findBy(['isActive' => true]);#-- comme en SQL : " SELECT * FROM montres WHERE isActive = true " pour récuprer uniquement les montres activées--#
        return $this->render('home/index.html.twig', [
            'montres' => $montres,
        ]);
    }
    #[Route('/montre/{id}', name:'app_montre_index')]
    public function montreindex(Montre $montre): Response
    {
        return $this->render('home/montre_show.html.twig', [
            'montre' => $montre,
            ]);
    }
}
  