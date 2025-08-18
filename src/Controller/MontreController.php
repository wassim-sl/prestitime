<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Form\MontreType;
use App\Repository\MontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/montre')]
final class MontreController extends AbstractController
{
    #[Route(name: 'app_montre_index', methods: ['GET'])]
    public function index(MontreRepository $montreRepository): Response
    {
        return $this->render('montre/index.html.twig', [
            'montres' => $montreRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_montre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $montre = new Montre();
        $form = $this->createForm(MontreType::class, $montre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($montre);
            $entityManager->flush();
            $this->addFlash('success', 'La montre "' . $montre->getmarque()->getNom() . '" a bien été ajouté');


            return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Action refusée : veuillez remplir tous les champs du formulaire ! ');
        }

        return $this->render('montre/new.html.twig', [
            'montre' => $montre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_montre_show', methods: ['GET'])]
    public function show(Montre $montre): Response
    {
        return $this->render('montre/show.html.twig', [
            'montre' => $montre,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_montre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Montre $montre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MontreType::class, $montre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('montre/edit.html.twig', [
            'montre' => $montre,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_montre_delete', methods: ['POST'])]
    public function delete(Request $request, Montre $montre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $montre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($montre);
            $entityManager->flush();
            $this->addFlash('success', 'La montre "' . $montre->getmarque() . '" a bien été supprimée.');
        }

        return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admin/montre/{id}/toggle', name: 'montre_toggle')]
    public function toggle(Montre $montre, EntityManagerInterface $em): Response
    {
        // Inverser l'état de isActive (true devient false, false devient true)
        $montre->setIsActive(!$montre->isActive());
        $em->flush();

        return $this->redirectToRoute('app_montre_index'); // redirige vers la liste
    }
    
}
