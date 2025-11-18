<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    // Route pour accéder à la page d'inscription (URL : /inscription)
#[Route('/inscription', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    // Création d'un nouvel utilisateur (Client)
    $user = new Client();

    // Création du formulaire d'inscription basé sur RegistrationFormType
    $form = $this->createForm(RegistrationFormType::class, $user);

    // Récupération et traitement des données envoyées par le formulaire
    $form->handleRequest($request);

    // Vérifie si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {

        // Récupération du mot de passe saisi en clair dans le formulaire
        $plainPassword = $form->get('plainPassword')->getData();

        // Chiffrement (hachage) du mot de passe avant de l’enregistrer en base
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        // Prépare l’enregistrement du nouvel utilisateur dans la base de données
        $entityManager->persist($user);

        // Exécute la requête d’insertion (sauvegarde dans la base)
        $entityManager->flush();

        // Redirige l’utilisateur vers la page de connexion après l’inscription
        return $this->redirectToRoute('app_login');
    }

    // Affiche la page d'inscription avec le formulaire (si non soumis ou invalide)
    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form,
    ]);
}

}
