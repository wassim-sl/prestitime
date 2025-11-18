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

    // Route pour ajouter une nouvelle montre dans l’espace administrateur
#[Route('/admin/new', name: 'app_montre_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    // Création d’un nouvel objet Montre
    $montre = new Montre();

    // Création du formulaire à partir de la classe MontreType
    $form = $this->createForm(MontreType::class, $montre);

    // Récupération des données envoyées via le formulaire
    $form->handleRequest($request);

    // Vérification si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement de la montre dans la base de données
        $entityManager->persist($montre);
        $entityManager->flush();

        // Message de confirmation à l’utilisateur
        $this->addFlash('success', 'La montre "' . $montre->getmarque()->getNom() . '" a bien été ajoutée.');

        // Redirection vers la liste des montres
        return $this->redirectToRoute('app_montre_index', [], Response::HTTP_SEE_OTHER);
    }

    // Si le formulaire est soumis mais invalide
    if ($form->isSubmitted() && !$form->isValid()) {
        // Message d’erreur à l’utilisateur
        $this->addFlash('error', 'Action refusée : veuillez remplir tous les champs du formulaire !');
    }

    // Affichage du formulaire dans la vue Twig
    return $this->render('montre/new.html.twig', [
        'montre' => $montre,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_montre_show', requirements: ['id' => '\d+'], methods: ['GET'])]
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

    #[Route('/recherche', name: 'recherche', methods: ['GET'])]
    public function recherche(Request $request, EntityManagerInterface $em): Response
    {
        // Récupère la valeur saisie dans l'URL (ex: ?q=Rolex)
        $query = trim($request->query->get('q', ''));

        // On construit une requête sur l'entité Montre
        $qb = $em->getRepository(Montre::class)->createQueryBuilder('m')
            // Jointure pour accéder au nom de la marque
            ->leftJoin('m.marque', 'ma')->addSelect('ma')
            // Jointure pour accéder au nom de la catégorie
            ->leftJoin('m.categorie', 'ca')->addSelect('ca');

        // Filtre la recherche sur marque OU catégorie si $query n’est pas vide
        if ($query !== '') {
            $qb->andWhere('ma.nom LIKE :q OR ca.nom LIKE :q')
                ->setParameter('q', '%' . $query . '%'); // recherche partielle
        }

        // Exécute la requête et récupère les résultats
        $montres = $qb->getQuery()->getResult();

        // Envoie les résultats à la vue
        return $this->render('montre/recherche.html.twig', [
            'montres' => $montres,
            'query'   => $query,
        ]);
    }
    // src/Controller/MontreController.php

    #[Route('/homme', name: 'homme')]
    public function homme(MontreRepository $repo): Response
    {
        $montres = $repo->createQueryBuilder('m')
            ->join('m.categorie', 'c')          // si m.categorie est une relation vers Categorie
            ->andWhere('LOWER(c.nom) = :nom')
            ->setParameter('nom', 'Montre homme') // valeur EXACTE en BDD (insensible à la casse)
            ->orderBy('m.id', 'ASC')
            ->getQuery()->getResult();

        return $this->render('montre/index.html.twig', [
            'montres' => $montres,
            'filtre'  => 'Montres homme',
        ]);
    }

   

    #[Route('/categorie/{slug}', name: 'categorie')] // /montre/categorie/homme|femme
    public function categorie(MontreRepository $repo, string $slug): Response
    {
        // slug attendu : "homme" ou "femme"
        $needle = $slug === 'homme' ? 'montre homme' : 'montre femme';

        // Si m.categorie est une RELATION vers Categorie:
        $montres = $repo->createQueryBuilder('m')
            ->join('m.categorie', 'c')
            ->andWhere('LOWER(c.nom) = :nom')
            ->setParameter('nom', $needle)
            ->orderBy('m.id', 'ASC')
            ->getQuery()->getResult();

        // (Si c’est un champ texte: remplace le join par ->andWhere('LOWER(m.categorie) = :nom'))

        $title = $slug === 'homme' ? 'Montres Homme' : 'Montres Femme';

        return $this->render('montre/categorie.html.twig', [
            'montres' => $montres,
            'title'   => $title,
        ]);
    }

    

}
