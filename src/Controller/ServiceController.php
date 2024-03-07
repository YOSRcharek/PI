<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Categorie;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\Association;
use Symfony\Component\Security\Core\Security;


class ServiceController extends AbstractController
{
    private $security;


public function __construct(Security $security)
{
   
    $this->security = $security;
}
    #[Route('front/allservices', name: 'app_allservice', methods: ['GET'])]
    
    public function service(ServiceRepository $ServiceRepository): Response
    {
        return $this->render('front/Service/all.html.twig', [
            'Services' => $ServiceRepository->findAll(),
        ]);
        
    }

    #[Route('front/Services', name: 'app_Service_index', methods: ['GET'])]
    public function serviceback(EntityManagerInterface $entityManager, Security $security): Response
    {
        // Get the current user
        $user = $security->getUser();
    
        // Check if the user is authenticated
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }
    
        // Retrieve the associated association using the user's email
        $association = $entityManager->getRepository(Association::class)->findOneBy(['email' => $user->getEmail()]);
    
        // If no association found, return an error response or handle accordingly
        if (!$association) {
            throw $this->createNotFoundException('Association not found.');
        }
    
        // Retrieve services associated with the association
        $services = $association->getServices();
    
        return $this->render('front/Service/index.html.twig', [
            'Services' => $services,
        ]);
    }   

    #[Route('/newService', name: 'app_Service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        $user = $security->getUser();

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }

        $email = $user->getEmail();

        // Trouver l'association par son adresse e-mail
        $association = $entityManager->getRepository(Association::class)->findOneBy(['email' => $email]);
        $Service = new Service();
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Service->setAssociation($association);

            $entityManager->persist($Service);
            $entityManager->flush();

            return $this->redirectToRoute('app_Service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/Service/new.html.twig', [
            'Service' => $Service,
            'form' => $form,
        ]);
    }

    #[Route('/Service/{id}', name: 'app_Service_show', methods: ['GET'])]
    public function show(Service $Service): Response
    {
      
        return $this->render('front/Service/show.html.twig', [
            'Service' => $Service,
        ]);
    }
    

    #[Route('/editService/{id}', name: 'app_Service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $Service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_Service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/back/Service/edit.html.twig', [
            'Service' => $Service,
            'form' => $form,
        ]);
    }

    #[Route('/deleteService/{id}', name: 'app_Service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $Service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($Service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Service_index', [], Response::HTTP_SEE_OTHER);
    }
}
