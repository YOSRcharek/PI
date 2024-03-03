<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use CalendarBundle\Entity\Event;

class CalendrierController extends AbstractController
{
    
    #[Route('/calendrier', name: 'app_calendrier')]
    public function afficher(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->getNomEvent(),
                'start' => $event->getDateDebut()->format('Y-m-d H:i:s'),
                'end' => $event->getDateFin()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->render('event/calendrier.html.twig', [
            'events' => json_encode($formattedEvents),
        ]);
    }
}
