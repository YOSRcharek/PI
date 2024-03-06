<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Association;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserFormType;
use App\Form\UserdataprofileType;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class ProjetController extends AbstractController
{
    private $security;


public function __construct(Security $security)
{
   
    $this->security = $security;
}
    #[Route('/projet', name: 'app_projet')]
    public function index(): Response
    {
        return $this->render('projet/index.html.twig', [
            'controller_name' => 'ProjetController',
        ]);
    }
    #[Route('/createProjet', name: 'app_create_projet')]
public function create(EntityManagerInterface $entityManager, Request $request): Response
{
    $projet = new Projet();
    $form = $this->createForm(ProjetType::class, $projet, [
        'associations' => $this->getDoctrine()->getRepository(Association::class)->findBy(['status' => true])
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_projet');
    }

    return $this->render('projet/add.html.twig', ['form' => $form->createView()]);
}
#[Route('/createProjetFront', name: 'app_create_projetFront')]
    public function createFront(Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }

        // Récupérer l'adresse e-mail de l'utilisateur connecté
        $email = $user->getEmail();

        // Trouver l'association par son adresse e-mail
        $association = $entityManager->getRepository(Association::class)->findOneBy(['email' => $email]);

        if (!$association) {
            throw $this->createNotFoundException('Association not found for the logged-in user.');
        }

        // Créer un nouveau projet
        $projet = new Projet();

        // Créer le formulaire pour le projet
        $form = $this->createForm(ProjetType::class, $projet);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le projet à l'association
            $projet->setAssociation($association);

            // Enregistrer le projet
            $entityManager->persist($projet);
            $entityManager->flush();

            // Rediriger vers la page du profil après l'enregistrement du projet
            return $this->redirectToRoute('app_profil');
        }

        // Afficher le formulaire de création de projet
        return $this->render('projet/addFront.html.twig', [
            'form' => $form->createView(),
        ]);
    }

#[Route('/editProjet/{id}', name: 'app_edit_projet')]
public function edit(Request $request, EntityManagerInterface $entityManager, ProjetRepository $projetRepo, int $id): Response
{
    $projet = $projetRepo->find($id);

    if (!$projet) {
        throw $this->createNotFoundException('Projet not found');
    }

    $form = $this->createForm(ProjetType::class, $projet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_profil');
    }

    return $this->render('projet/edit.html.twig', [
        'form' => $form->createView(),
        'projet' => $projet
    ]);
}


    #[Route('detailProjet/{id}', name: 'app_details_projet')]
    public function showDetails(projetRepository $projetRepo, $id): Response
    {
        return $this->render('projet/details.html.twig', [
            'projet' => $projetRepo->find($id),
        ]);
    }
    #[Route('/deleteProjet/{id}', name: 'app_delete_projet')]
    public function delete(Request $request, $id, ManagerRegistry $manager,projetRepository $projetRepo): Response
    {
        // Action pour supprimer une projet
        $em = $manager->getManager();
        $projet = $projetRepo->find($id);

        $em->remove($projet);
        $em->flush();
        return $this->redirectToRoute('app_home');  // Modifié pour utiliser le nom de la route correct
    }

    #[Route('/projets', name: 'app_show_projet')]
    public function show(projetRepository $projetRepo): Response
    {
        $projets = $projetRepo->findAll();
        return $this->render('admin/projets.html.twig', ['projets' => $projets]);
    }
    #[Route('/editProjetAdmin/{id}', name: 'app_edit_projetAdmin')]
public function editAdmin(Request $request, EntityManagerInterface $entityManager, ProjetRepository $projetRepo, int $id): Response
{
    $projet = $projetRepo->find($id);

    if (!$projet) {
        throw $this->createNotFoundException('Projet not found');
    }

    $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_show_projet');
    }

    return $this->render('projet/editAdmin.html.twig', [
        'form' => $form->createView(),
    ]);
}
/**
     * @Route("/project/stats", name="project_stats")
     */
    public function projectStats(ProjetRepository $projetRepository): JsonResponse
    {
        $ongoingProjectsCount = $projetRepository->countOngoingProjects();
        $completedProjectsCount = $projetRepository->countCompletedProjects();

        return $this->json([
            'ongoingProjectsCount' => $ongoingProjectsCount,
            'completedProjectsCount' => $completedProjectsCount,
        ]);
    }
    #[Route('/search_project_ajax', name: 'search_project_ajax')]
public function searchProjectAjax(Request $request, ProjetRepository $projetRepository): JsonResponse
{
    $query = $request->query->get('query');

    // Effectuer une recherche dans le repository ProjetRepository
    $results = $projetRepository->createQueryBuilder('p')
        ->where('p.nomProjet LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();

    $formattedResults = [];
    foreach ($results as $result) {
        $formattedResults[] = [
            'id' => $result->getId(),
            'nomProjet' => $result->getNomProjet(),
            'description' => $result->getDescription(),
            'dateDebut' => $result->getDateDebut(),
            'dateFin' => $result->getDateFin(),
            'status' => $result->getStatus(),

        ];
    }

    if (!empty($formattedResults)) {
        $response = ['results' => $formattedResults, 'message' => 'Résultats trouvés.'];
    } else {
        $response = ['results' => [], 'message' => 'Aucun résultat trouvé.'];
    }

    return new JsonResponse($response);
}

}

