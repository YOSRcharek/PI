<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function news(): Response
    {
        return $this->render('/home/news.html.twig');
    }
    
    public function newsDetail(): Response
    {
        return $this->render('/home/news-detail.html.twig');
    }
    public function donate(): Response
    {
        return $this->render('/home/donate.html.twig');
    }
    #[Route('/', name: 'app_home')]
    public function Home(): Response
    {
        $newsContent = $this->renderView('/home/news.html.twig');

        return $this->render('base.html.twig', [
            'newsContent' => $newsContent,
        ]
        );
    }
    
}

