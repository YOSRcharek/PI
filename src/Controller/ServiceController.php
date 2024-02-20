<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/')]
class ServiceController extends AbstractController
{
    #[Route('/Services', name: 'app_Service_index', methods: ['GET'])]
    
    public function index(ServiceRepository $ServiceRepository): Response
    {
        return $this->render('Service/index.html.twig', [
            'Services' => $ServiceRepository->findAll(),
        ]);
    }




    #[Route('/newService', name: 'app_Service_new', methods: ['GET', 'POST'])]
    #[ParamConverter("service", class:"App\Entity\Service")]
 public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $Service = new Service();
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Service);
            $entityManager->flush();

            return $this->redirectToRoute('app_Service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Service/new.html.twig', [
            'Service' => $Service,
            'form' => $form,
        ]);
    }

    #[Route('/Service/{id}', name: 'app_Service_show', methods: ['GET'])]
    #[ParamConverter("Service", class:"App\Entity\Service")]
    public function show(Service $Service): Response
    {
        return $this->render('Service/show.html.twig', [
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

        return $this->renderForm('Service/edit.html.twig', [
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
