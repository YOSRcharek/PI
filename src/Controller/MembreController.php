<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Association;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserFormType;
use App\Form\UserdataprofileType;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class MembreController extends AbstractController
{
    private $security;


public function __construct(Security $security)
{
   
    $this->security = $security;
}
    #[Route('/membre', name: 'app_membre')]
    public function index(): Response
    {
        return $this->render('membre/index.html.twig', [
            'controller_name' => 'MembreController',
        ]);
    }
    #[Route('/createMembre', name: 'app_create_membre')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $membre = new membre();
        $form = $this->createForm(MembreType::class, $membre, [
            'associations' => $this->getDoctrine()->getRepository(Association::class)->findBy(['status' => true])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_membre');
        }

        return $this->render('membre/add.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/createMembreFront', name: 'app_create_membreFront')]
    public function createFront(Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }

        $email = $user->getEmail();

        // Trouver l'association par son adresse e-mail
        $association = $entityManager->getRepository(Association::class)->findOneBy(['email' => $email]);

        if (!$association) {
            throw $this->createNotFoundException('Association not found for the logged-in user.');
        }

        // Créer un nouveau membre
        $membre = new Membre();

        // Créer le formulaire pour le membre
        $form = $this->createForm(MembreType::class, $membre);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le membre à l'association
            $membre->setAssociation($association);

            // Enregistrer le membre
            $entityManager->persist($membre);
            $entityManager->flush();

            // Rediriger vers la page du profil après l'enregistrement du membre
            return $this->redirectToRoute('app_profil');
        }

        // Afficher le formulaire de création de membre
        return $this->render('membre/addFront.html.twig', [
            'form' => $form->createView(),
        ]);
    }
#[Route('/editMembre/{id}', name: 'app_edit_membre')]
public function edit(Request $request, EntityManagerInterface $entityManager, MembreRepository $MembreRepo, int $id): Response
{
    $Membre = $MembreRepo->find($id);

    if (!$Membre) {
        throw $this->createNotFoundException('Membre not found');
    }

    $form = $this->createForm(MembreType::class, $Membre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_profil');
    }

    return $this->render('Membre/edit.html.twig', [
        'form' => $form->createView(),
        'membre' => $Membre
    ]);
}
    #[Route('detailMembre/{id}', name: 'app_details_membre')]
    public function showDetails(MembreRepository $membreRepo, $id): Response
    {
        return $this->render('membre/details.html.twig', [
            'membre' => $membreRepo->find($id),
        ]);
    }
    #[Route('/deleteMembre/{id}', name: 'app_delete_membre')]
    public function delete(Request $request, $id, ManagerRegistry $manager,MembreRepository $membreRepo): Response
    {
        // Action pour supprimer une membre
        $em = $manager->getManager();
        $membre = $membreRepo->find($id);

        $em->remove($membre);
        $em->flush();
        return $this->redirectToRoute('app_home');  // Modifié pour utiliser le nom de la route correct
    }
    #[Route('/membres', name: 'app_show_membre')]
    public function show(membreRepository $membreRepo): Response
    {
        $membres = $membreRepo->findAll();
        return $this->render('admin/membres.html.twig', ['membres' => $membres]);
    }
    #[Route('/editMembreAdmin/{id}', name: 'app_edit_membreAdmin')]
public function editAdmin(Request $request, EntityManagerInterface $entityManager, MembreRepository $MembreRepo, int $id): Response
{
    $Membre = $MembreRepo->find($id);

    if (!$Membre) {
        throw $this->createNotFoundException('Membre not found');
    }

    $form = $this->createForm(MembreType::class, $Membre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_show_membre');
    }

    return $this->render('membre/editAdmin.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/search_member_ajax', name: 'search_member_ajax')]
public function searchMemberAjax(Request $request, MembreRepository $MembreRepository): JsonResponse
{
    $query = $request->query->get('query');

    // Effectuer une recherche dans le repository membreRepository
    $results = $MembreRepository->createQueryBuilder('p')
        ->where('p.nomMembre LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();

    $formattedResults = [];
    foreach ($results as $result) {
        $formattedResults[] = [
            'id' => $result->getId(),
            'nomMembre' => $result->getNomMembre(),
            'prenomMembre' => $result->getPrenomMembre(),
            'fonction' => $result->getFonction(),
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
