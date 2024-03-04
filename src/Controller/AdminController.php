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
    #[Route('/typedonsadmin', name: 'typedons')]
    public function typedons(): Response
    {
   
        return $this->render('/admin/typedons.html.twig');
    }
    #[Route('/addadmin', name: 'type_dons_add_admin')]
    public function typedonsadd(): Response
    {
   
        return $this->render('/admin/addadmin.html.twig');
    }
    #[Route('/modifieradmin/{id}', name: 'type_dons_modifier_admin')]
    public function typedonsmodifier(): Response
    {
   
        return $this->render('/admin/modifieadmin.html.twig');
    }
    #[Route('/donsadmin', name: 'app_dons_admin')]
    public function dons(): Response
    {
   
        return $this->render('/admin/dons.html.twig');
    }
    #[Route('/donateadmin', name: 'dons_add_admin')]
    public function donsadd(): Response
    {
   
        return $this->render('/admin/adddonsadmin.html.twig');
    }
    #[Route('/modifierDonsAdmin/{id}', name: 'dons_modifier_admin')]
    public function modifierdonsadmin(): Response
    {
   
        return $this->render('/admin/modifierdonsadmin.html.twig');
    }
   
    #[Route('/buttons', name: 'buttons')]

    public function buttons(): Response
    {
        return $this->render('/admin/buttons.html.twig');
    }
    #[Route('/cards', name: 'cards')]
    public function cards(): Response
    {
        return $this->render('/admin/cards.html.twig');
    }
    #[Route('/charts', name: 'charts')]
    public function charts(): Response
    {
        return $this->render('/admin/charts.html.twig');
    }
    #[Route('/forms', name: 'forms')]
    public function forms(): Response
    {
        return $this->render('/admin/forms.html.twig');
    }
    #[Route('/modals', name: 'modals')]
    public function modals(): Response
    {
        return $this->render('/admin/modals.html.twig');
    }
    #[Route('/associations', name: 'associations')]
    public function associations(): Response
    {
        return $this->render('/admin/associations.html.twig');
    }

   
    #[Route('/demandes', name: 'demandes')]

    public function projets(): Response
    {
        return $this->render('/admin/projets.html.twig');
    }
    public function membres(): Response
    {
        return $this->render('/admin/membres.html.twig');
    }

    public function demandes(): Response
    {
        return $this->render('/admin/demandes.html.twig');
    }
    #[Route('/erreur', name: 'erreur')]
    public function erreur(): Response
    {
        return $this->render('/admin/pages/404.html.twig');
    }
    #[Route('/blank', name: 'blank')]
    public function blank(): Response
    {
        return $this->render('/admin/pages/blank.html.twig');
    }
    #[Route('/createAcc', name: 'createAcc')]
    public function createAcc(): Response
    {
        return $this->render('/home/create-account.html.twig');
    }
    #[Route('/forgetPass', name: 'forgetPass')]
    public function forgetPass(): Response
    {
        return $this->render('/admin/pages/forget-password.html.twig');
    }
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('/admin/pages/login.html.twig');
    }
    
}
