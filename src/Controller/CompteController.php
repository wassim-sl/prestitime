<?php

namespace App\Controller;
use App\Form\EditAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CompteController extends AbstractController
{
    #[Route('/compte/edit', name: 'app_edit_compte')]
    public function edit(Request $request, EntityManagerInterface $em, UserInterface $user): Response
    {
        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compte mis à jour avec succès.');
            return $this->redirectToRoute('app_edit_compte');
        }

        return $this->render('compte/edit_compte.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
}
