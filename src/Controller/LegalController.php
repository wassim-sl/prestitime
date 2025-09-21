<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    // Mentions légales
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentions(): Response
    {
        return $this->render('legal/mentions.html.twig');
    }

    // Conditions Générales d’Utilisation (CGU)
    #[Route('/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    // Conditions Générales de Vente (CGV)
    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/cgv.html.twig');
    }

    // Politique de confidentialité
    #[Route('/confidentialite', name: 'app_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('legal/confidentialite.html.twig');
    }
    //a propos
    #[Route('/a-propos', name: 'app_a_propos')]
    public function aPropos(): Response
    {
        return $this->render('legal/a_propos.html.twig');
    }
}