<?php

namespace App\Controller;

use App\Entity\TypeDons; 
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
        die("Controller method executed.");
        $typeDons = $typeDonsRepository->findAll();

        return $this->render('admin/typedons.html.twig', [
            'type_dons' => $typeDons,
        ]);
    }
    
    
    

}
