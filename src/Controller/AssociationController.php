<?php

namespace App\Controller;

use App\Entity\Association;  // Ajout de l'import pour la classe Association
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class AssociationController extends AbstractController
{
    #[Route('/association', name: 'app_show')]
    
    public function index(): Response
    {
        return $this->render('/association/index.html.twig', [
            'controller_name' => 'AssociationController',
        ]);
    }
    #[Route('/associations', name: 'app_show')]
    public function show(AssociationRepository $associationRepo): Response
    {
        $associations = $associationRepo->findByStatus(1);
        return $this->render('admin/associations.html.twig', ['associations' => $associations]);
    }
    #[Route('/demandes', name: 'app_demandes')]
    public function demandes(AssociationRepository $associationRepo): Response
    { 
        $demandes = $associationRepo->findByStatus(0);

         return $this->render('admin/demandes.html.twig', ['demandes' => $demandes]);
    }

#[Route('/create', name: 'app_create')]
public function create(EntityManagerInterface $entityManager, Request $request): Response
{
    $association = new Association();
    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($association);
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }

    return $this->render('association/create.html.twig', ['form' => $form->createView()]);
}

#[Route('/edit/{id}', name: 'app_edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, AssociationRepository $associationRepo, int $id): Response
{
    $association = $associationRepo->find($id);

    if (!$association) {
        throw $this->createNotFoundException('Association not found');
    }

    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }

    return $this->render('association/edit.html.twig', ['form' => $form->createView()]);
}

// ...


    #[Route('detail/{id}', name: 'app_details')]
    public function showDetails(AssociationRepository $AssociationRepo, $id): Response
    {
        return $this->render('associociation/details.html.twig', [
            'association' => $AssociationRepo->find($id),
        ]);
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(Request $request, $id, ManagerRegistry $manager,AssociationRepository $associationRepo): Response
    {
        // Action pour supprimer une association
        $em = $manager->getManager();
        $association = $associationRepo->find($id);

        $em->remove($association);
        $em->flush();
        return $this->redirectToRoute('app_show');  // Modifié pour utiliser le nom de la route correct
    }
    #[Route('/profil', name: 'app_profil')]
    
    public function profil(): Response
    {
        return $this->render('association/profile.html.twig'
        );
    }

public function findByStatus($status): array
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.status = :status')
        ->setParameter('status', $status)
        ->getQuery()
        ->getResult();
}

}