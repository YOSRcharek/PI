<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_Home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    public function news(): Response
    {
        return $this->render('news.html.twig');
    }
    
    public function newsDetail(): Response
    {
        return $this->render('news-detail.html.twig');
    }
    public function donate(): Response
    {
        return $this->render('donate.html.twig');
    }
    #[Route('/', name: 'app_home')]
    public function Home(): Response
    {
        $newsContent = $this->renderView('news.html.twig');

        return $this->render('base.html.twig', [
            'newsContent' => $newsContent,
        ]
        );
    }
    
}

