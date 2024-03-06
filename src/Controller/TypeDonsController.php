<?php

namespace App\Controller;

use App\Entity\TypeDons;
use App\Form\TypeDonsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeDonsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class TypeDonsController extends AbstractController
{
    

    #[Route('/typedons', name: 'type_dons_show', methods: ['GET'])]
    public function show(TypeDonsRepository $typeDonsRepository): Response
    {
        $typeDons = $typeDonsRepository->findAll();
    
        return $this->render('type_dons/show.html.twig', [
            'type_dons' => $typeDons,
        ]);
    }

    #[Route('/typedonsadmin', name: 'typedons', methods: ['GET'])]
    public function shows(TypeDonsRepository $typeDonsRepository): Response
    {
        $typeDons = $typeDonsRepository->findAll();
    
        return $this->render('admin/typedons.html.twig', [
            'type_dons' => $typeDons,
        ]);
    }
    
    #[Route('/add', name: 'type_dons_add', methods: ['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $typeDon = new TypeDons();

        $form = $this->createForm(TypeDonsType::class, $typeDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($typeDon);
            $entityManager->flush();

            $this->addFlash('success', 'Type de don ajouté avec succès.');

            return $this->redirectToRoute('type_dons_show');
        }

        return $this->render('type_dons/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/addadmin', name: 'type_dons_add_admin', methods: ['GET', 'POST'])]
    public function adds(Request $request, ManagerRegistry $doctrine): Response
    {
        $typeDon = new TypeDons();

        $form = $this->createForm(TypeDonsType::class, $typeDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($typeDon);
            $entityManager->flush();

            $this->addFlash('success', 'Type de don ajouté avec succès.');

            return $this->redirectToRoute('typedons');
        }

        return $this->render('admin/addadmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/modifier/{id}', name: 'type_dons_modifier', methods: ['GET', 'POST'])]
    public function modifier(Request $request, EntityManagerInterface $entityManager, $id, TypeDonsRepository $typeDonsRepository): Response
    {
        $typeDons = $typeDonsRepository->find($id);

        $form = $this->createForm(TypeDonsType::class, $typeDons);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le type de don a été modifié avec succès.');

            return $this->redirectToRoute('type_dons_show', ['id' => $typeDons->getId()]);
        }

        return $this->render('type_dons/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifieradmin/{id}', name: 'type_dons_modifier_admin', methods: ['GET', 'POST'])]
    public function modifiers(Request $request, EntityManagerInterface $entityManager, $id, TypeDonsRepository $typeDonsRepository): Response
    {
        $typeDons = $typeDonsRepository->find($id);

        $form = $this->createForm(TypeDonsType::class, $typeDons);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le type de don a été modifié avec succès.');

            return $this->redirectToRoute('typedons', ['id' => $typeDons->getId()]);
        }

        return $this->render('admin/modifieadmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deleteTypeDons/{id}', name: 'type_dons_delete')]
    public function deleteTypeDons(Request $request, $id, ManagerRegistry $manager, TypeDonsRepository $typeDonsRepository): Response
    {
        $em = $manager->getManager();
        $typeDons = $typeDonsRepository->find($id);

        if (!$typeDons) {
            throw $this->createNotFoundException('Type de don non trouvé');
        }

        $em->remove($typeDons);
        $em->flush();

        $this->addFlash('success', 'Type de don supprimé avec succès.');

        return $this->redirectToRoute('type_dons_show');
    }

    #[Route('/deleteTypeDonsadmin/{id}', name: 'type_dons_delete_admin')]
    public function deleteTypeDonsadmin(Request $request, $id, ManagerRegistry $manager, TypeDonsRepository $typeDonsRepository): Response
    {
        $em = $manager->getManager();
        $typeDons = $typeDonsRepository->find($id);

        if (!$typeDons) {
            throw $this->createNotFoundException('Type de don non trouvé');
        }

        $em->remove($typeDons);
        $em->flush();

        $this->addFlash('success', 'Type de don supprimé avec succès.');

        return $this->redirectToRoute('typedons');
    }
}
