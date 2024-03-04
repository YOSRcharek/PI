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

class ProjetController extends AbstractController
{
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

        return $this->redirectToRoute('app_home');
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

}

