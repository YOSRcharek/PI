<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
   
        return $this->render('baseAdmin.html.twig');
    }
    
    
    public function buttons(): Response
    {
        return $this->render('/admin/buttons.html.twig');
    }
    public function cards(): Response
    {
        return $this->render('/admin/cards.html.twig');
    }
    public function charts(): Response
    {
        return $this->render('/admin/charts.html.twig');
    }
    public function forms(): Response
    {
        return $this->render('/admin/forms.html.twig');
    }
    public function modals(): Response
    {
        return $this->render('/admin/modals.html.twig');
    }
    public function associations(): Response
    {
        return $this->render('/admin/associations.html.twig');
    }
    public function demandes(): Response
    {
        return $this->render('/admin/demandes.html.twig');
    }
    public function erreur(): Response
    {
        return $this->render('/admin/pages/404.html.twig');
    }
    public function blank(): Response
    {
        return $this->render('/admin/pages/blank.html.twig');
    }
    public function createAcc(): Response
    {
        return $this->render('/admin/pages/create-account.html.twig');
    }
    public function forgetPass(): Response
    {
        return $this->render('/admin/pages/forget-password.html.twig');
    }
    public function login(): Response
    {
        return $this->render('/admin/pages/login.html.twig');
    }
}
