<?php

namespace App\Controller;

use App\Service\NewsAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerTraitement;
use Symfony\Component\Mailer\MailerInterface;

class HomeController extends AbstractController
{
    private $newsAPIService;

    public function __construct(NewsAPIService $newsAPIService)
    {
        $this->newsAPIService = $newsAPIService;
    }

    public function news(): Response
    {
       

        return $this->render('/home/news.html.twig');
    }

    public function newsDetail(): Response
    {
       
        return $this->render('/home/news-detail.html.twig');
    }
    public function newsAPI(): Response
    {
        $associationNews = $this->newsAPIService->getAssociationNews();
        return $this->render('/home/newsAPI.html.twig', [
            'associationNews' => $associationNews,
        ]);
    }
    public function donate(): Response
    {
        return $this->render('/home/donate.html.twig');
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $associationNews = $this->newsAPIService->getAssociationNews();

        return $this->render('base.html.twig', [
            'associationNews' => $associationNews,
        ]);
    }

    public function handleContactForm( Request $request, MailerTraitement $mail): Response
{
    // Handle form submission
    if ($request->isMethod('POST')) {
        // Retrieve form data
        $firstName = $request->request->get('first-name');
        $lastName = $request->request->get('last-name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        // Process form data (e.g., send email, save to database, etc.)
        // Send email
        $mail->sendEmailContact($email, $firstName, $lastName, $message);

        // Render a success message
        $successMessage = 'Your message has been sent successfully!';
        return $this->render('home/index.html.twig', [
            'message' => $successMessage,
        ]);
    }

    // Render the form for GET requests
    return $this->render('home/index.html.twig');
}
    // Your other methods...

}
