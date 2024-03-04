<?php

namespace App\Controller;
use App\Entity\Dons;
use App\Form\DonsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DonsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class DonsController extends AbstractController
{
    #[Route('/dons', name: 'app_dons')]
    public function show(DonsRepository $DonsRepository): Response
    {
        $Dons = $DonsRepository->findAll();
        return $this->render('dons/show.html.twig', [
            'Dons' => $Dons,
        ]);
    }
    #[Route('/donsadmin', name: 'app_dons_admin')]
    public function shows(DonsRepository $DonsRepository): Response
    {
        $Dons = $DonsRepository->findAll();
        return $this->render('admin/dons.html.twig', [
            'Dons' => $Dons,
        ]);
    }
    #[Route('/donate', name: 'dons_add')]
    public function add(Request $request): Response
    {
        $dons = new Dons();
    
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($dons);
                $entityManager->flush();
    
                $this->addFlash('success', 'Don ajouté avec succès.');
    
                return $this->redirectToRoute('app_dons');
            }
        }catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'ajout d\'un don .');
        }
        return $this->render('home/donate.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/donateadmin', name: 'dons_add_admin')]
    public function adds(Request $request): Response
    {
        $dons = new Dons();
    
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($dons);
                $entityManager->flush();
    
                $this->addFlash('success', 'Don ajouté avec succès.');
    
                return $this->redirectToRoute('app_dons_admin');
            }
        }catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'ajout d\'un don .');
        }
        return $this->render('admin/adddonsadmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifierDons/{id}', name: 'dons_modifier', methods: ['GET', 'POST'])]
    public function modifier(Request $request, EntityManagerInterface $entityManager, $id, DonsRepository $donsRepository): Response
    {
        $dons = $donsRepository->find($id);
    
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Le don a été modifié avec succès.');
    
            return $this->redirectToRoute('app_dons');
        }
    
        return $this->render('dons/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/modifierDonsAdmin/{id}', name: 'dons_modifier_admin', methods: ['GET', 'POST'])]
    public function modifieradmin(Request $request, EntityManagerInterface $entityManager, $id, DonsRepository $donsRepository): Response
    {
        $dons = $donsRepository->find($id);
    
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Le don a été modifié avec succès.');
    
            return $this->redirectToRoute('app_dons_admin');
        }
    
        return $this->render('admin/modifierdonsadmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/deleteDons/{id}', name: 'dons_delete')]
    public function deleteDons(Request $request, $id, EntityManagerInterface $entityManager, DonsRepository $donsRepository): Response
    {
        $dons = $donsRepository->find($id);
    
        if (!$dons) {
            throw $this->createNotFoundException('Don non trouvé');
        }
    
        $entityManager->remove($dons);
        $entityManager->flush();
    
        $this->addFlash('success', 'Don supprimé avec succès.');
    
        return $this->redirectToRoute('app_dons');
    }


    #[Route('/deleteDonsAdmin/{id}', name: 'dons_delete_admin')]
    public function deleteDonsAdmin(Request $request, $id, EntityManagerInterface $entityManager, DonsRepository $donsRepository): Response
    {
        $dons = $donsRepository->find($id);
    
        if (!$dons) {
            throw $this->createNotFoundException('Don non trouvé');
        }
    
        $entityManager->remove($dons);
        $entityManager->flush();
    
        $this->addFlash('success', 'Don supprimé avec succès.');
    
        return $this->redirectToRoute('app_dons_admin');
    }
    




}
