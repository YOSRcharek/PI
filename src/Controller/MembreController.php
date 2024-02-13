<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MembreController extends AbstractController
{
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
    $form = $this->createForm(membreType::class, $membre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($membre);
        $entityManager->flush();

        return $this->redirectToRoute('app_membre');
    }

    return $this->render('membre/create.html.twig', ['form' => $form->createView()]);
}

#[Route('/editMembre/{id}', name: 'app_edit_membre')]
public function edit(Request $request, EntityManagerInterface $entityManager, MembreRepository $membreRepo, int $id): Response
{
    $membre = $membreRepo->find($id);

    if (!$membre) {
        throw $this->createNotFoundException('membre not found');
    }

    $form = $this->createForm(membreType::class, $membre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_membre');
    }

    return $this->render('membre/edit.html.twig', ['form' => $form->createView()]);
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
        return $this->redirectToRoute('app_membre');  // Modifié pour utiliser le nom de la route correct
    }
}
