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
use Knp\Component\Pager\PaginatorInterface;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;


class DonsController extends AbstractController
{
    #[Route('/dons', name: 'app_dons')]
    public function show(DonsRepository $DonsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        

        $pagination = $paginator->paginate(
            $DonsRepository->paginationQuery(),
            $request->query->getInt('page',1),
            6
        );

        return $this->render('dons/show.html.twig', [
            'pagination' => $pagination,
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
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $dons = new Dons();
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
    
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $doctrine->getManager();
                $entityManager->persist($dons);
                $entityManager->flush();
                 //envoyer le montant
                $montant = $dons->getMontant();
    
                $this->addFlash('success', 'Don ajouté avec succès.');
    
                
                return $this->redirectToRoute('app_stripe', ['montant' => $montant]);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'ajout d\'un don .');
        }
    
        return $this->render('home/donate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/donateadmin', name: 'dons_add_admin')]
    public function adds(Request $request, ManagerRegistry $doctrine): Response
    {
        $dons = new Dons();
    
        $form = $this->createForm(DonsType::class, $dons);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $doctrine->getManager();
                $entityManager->persist($dons);
                $entityManager->flush();
    
                $this->addFlash('success', 'Don ajouté avec succès.');
    
                return $this->redirectToRoute('app_dons_admin');
            }
        } catch (\Exception $e) {
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

    #[Route('/stripe', name: 'app_stripe')]
    public function index(Request $request): Response
    {
        
        $montant = $request->query->get('montant') *100;
    
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'montant' => $montant,
        ]);
    }
    

    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request): Response
    {
    
        $montant = $request->request->get('montant');
    
        try {
            \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
            \Stripe\Charge::create([
                "amount" => $montant,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
            ]);
    
            $this->addFlash('success', 'Payment Successful!');
        } catch (ApiErrorException $e) {
            $this->addFlash('error', 'An error occurred while processing the payment.');
        }
    
        return $this->redirectToRoute('app_dons', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/filter-dons-by-montant', name: 'filter_dons_by_montant')]
    public function filterDonsByMontant(Request $request, DonsRepository $donsRepository): JsonResponse
    {
        $montant = $request->query->get('montant');
    
        $dons = $donsRepository->findByMontant($montant);
        $donsArray = [];
        foreach ($dons as $don) {
            $donsArray[] = [
                'id' => $don->getId(),
                'montant' => $don->getMontant(),
                'dateMisDon' => $don->getDateMisDon()->format('d-m-Y'),
                'association' => $don->getAssociation() !== null ? $don->getAssociation()->getNom() : null,
                'type' => $don->getType() !== null ? $don->getType()->getNom() : null,
            ];
        }
        return new JsonResponse(['dons' => $donsArray]);
    }


    #[Route('/filter-dons-by-date-mis', name: 'filter_dons_by_date_mis')]
    public function filterDonsByDateMis(Request $request, DonsRepository $donsRepository): JsonResponse
    {
        $dateMis = $request->query->get('dateMis');
        $dateMisDateTime = new \DateTime($dateMis);
        $dons = $donsRepository->findByDateMisDon($dateMisDateTime);
        $donsArray = [];
        foreach ($dons as $don) {
            $donsArray[] = [
                'id' => $don->getId(),
                'montant' => $don->getMontant(),
                'dateMisDon' => $don->getDateMisDon()->format('d-m-Y'),
                'association' => $don->getAssociation() !== null ? $don->getAssociation()->getNom() : null,
                'type' => $don->getType() !== null ? $don->getType()->getNom() : null,
            ];
        }
        return new JsonResponse(['dons' => $donsArray]);
    }

      

    #[Route('/sort-dons', name: 'sort_dons')]
    public function sortDons(Request $request, DonsRepository $donsRepository): JsonResponse
    {
        $sortBy = $request->query->get('sortBy');
        $dons = $donsRepository->findBy([], [$sortBy => 'ASC']);
        $donsArray = [];
        foreach ($dons as $don) {
            $donsArray[] = [
                'id' => $don->getId(),
                'montant' => $don->getMontant(),
                'dateMisDon' => $don->getDateMisDon()->format('d-m-Y'),
                'association' => $don->getAssociation() !== null ? $don->getAssociation()->getNom() : null,
                'type' => $don->getType() !== null ? $don->getType()->getNom() : null,
            ];
        }
        return new JsonResponse(['dons' => $donsArray]);
    }
   
}

