<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerTraitement
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(private MailerInterface $mailer)
    {
        // Le constructeur est correctement déclaré
    }

    public function sendEmail(string $toEmail): void
    {
        $email = (new Email())
            ->from('classyy2023@gmail.com')
            ->to($toEmail)
            ->subject('TuniCollab demande')
            ->text('Votre demande est en cours de traitement  ')
            ->html('<p>Veuillez attendre la réponse de l\'administration</p>'); // Correction de la faute de frappe

        $this->mailer->send($email);
    }
    public function sendEmailContact(string $fromEmail, string $nom, string $prenom, string $msg): void
{
    $subject = $nom . ' ' . $prenom;
    
    $email = (new Email())
        ->from($fromEmail)
        ->to('classyy2023@gmail.com')
        ->subject($subject)
        ->text($msg); // Add a semicolon here

    $this->mailer->send($email);
}

    function sendActivationEmail(MailerInterface $mailer, string $email, string $token): void
    {
        $message = (new Email())
            ->from('classyy2023@gmail.com')
            ->to($email)
            ->subject('Activation de votre compte')
            ->html('<p>Veuillez cliquer sur le lien suivant pour activer votre compte :</p>
                    <p><a href="http://127.0.0.1:8000/activation?token='.$token.'">Activer mon compte</a></p>');

        $mailer->send($message);
    }
}
