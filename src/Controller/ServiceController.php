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
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;




#[Route('/Service')]
class ServiceController extends AbstractController
{
    #[Route('front/Services', name: 'app_Service_index', methods: ['GET'])]
    
    public function service(ServiceRepository $ServiceRepository): Response
    {
        return $this->render('front/Service/index.html.twig', [
            'Services' => $ServiceRepository->findAll(),
        ]);
    }


    #[Route('back/Services', name: 'app_Service_back_index', methods: ['GET'])]
    
    public function serviceback(ServiceRepository $ServiceRepository): Response
    {
        return $this->render('back/Service/index.html.twig', [
            'Services' => $ServiceRepository->findAll(),
        ]);
    }

    #[Route('/newService', name: 'app_Service_new', methods: ['GET', 'POST'])]
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

        return $this->renderForm('front/Service/new.html.twig', [
            'Service' => $Service,
            'form' => $form,
        ]);
    }
    #[Route('back/newService', name: 'app_Service_newback', methods: ['GET', 'POST'])]
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $Service = new Service();
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Service);
            $entityManager->flush();

            return $this->redirectToRoute('app_Service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/Service/new.html.twig', [
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
    }#[Route('back/Service/{id}', name: 'app_Service_showback', methods: ['GET'])]
    public function showback(Service $Service): Response
    {
      
        return $this->render('back/Service/showadmin.html.twig', [
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
    #[Route('/search_service', name: 'search_service')]
    public function searchService(Request $request, ServiceRepository $serviceRepository): Response
    {
        $query = $request->query->get('query');
    
        // Effectuez la requête de recherche en utilisant Doctrine ORM
        $results = $serviceRepository->createQueryBuilder('s')
            ->where('s.nomService LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    
        // Renvoyez les résultats à la vue
        return $this->render('front/Service/search_results.html.twig', [
            'services' => $results,
        ]);
    }
    #[Route('/service/{id}/qr-code', name: 'service_qr_code')]
    public function qrCode(int $id, ServiceRepository $repository, Endroid\QrCode\QrCodeFactoryInterface $qrCodeFactory)
    {
        $service = $repository->find($id);
        $qrCode = $qrCodeFactory->create($service->getNomService());
    
        // Vous pouvez personnaliser le QR code ici
        $qrCode->setSize(300); // Définit la taille du QR code
    $qrCode->setMargin(10); // Définit la marge autour du QR code
    $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]); // Définit la couleur de premier plan
    $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]); // Définit la couleur d'arrière-plan

    
        return new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }
   
    
    
    

    

}
