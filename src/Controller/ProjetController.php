<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    $projet = new projet();
    $form = $this->createForm(projetType::class, $projet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('app_projet');
    }

    return $this->render('projet/create.html.twig', ['form' => $form->createView()]);
}

#[Route('/editProjet/{id}', name: 'app_edit_projet')]
public function edit(Request $request, EntityManagerInterface $entityManager, projetRepository $projetRepo, int $id): Response
{
    $projet = $projetRepo->find($id);

    if (!$projet) {
        throw $this->createNotFoundException('projet not found');
    }

    $form = $this->createForm(projetType::class, $projet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_projet');
    }

    return $this->render('projet/edit.html.twig', ['form' => $form->createView()]);
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
        return $this->redirectToRoute('app_projet');  // Modifié pour utiliser le nom de la route correct
    }
}

