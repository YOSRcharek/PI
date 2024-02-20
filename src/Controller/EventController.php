<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface; // Ajoutez ceci
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;





class EventController extends AbstractController
{
    #[Route('/events', name: 'app_show_events')]
    public function showEvents(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('event/showAllevents.twig', ['event' => $events]);
    }
    #[Route('/event/create', name: 'app_create_event')]
    public function createEvent(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($event);
                $entityManager->flush();

                
                $this->addFlash('success', 'L\'événement a été créé avec succès!');
                return $this->redirectToRoute('app_show_events');
            }
        } catch (\Exception $e) {
            
            $this->addFlash('error', 'Une erreur s\'est produite lors de la création de l\'événement.');
        }
        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }
   

#[Route('/event/delete/{id}', name: 'app_delete_event')]
public function deleteEvent(Request $request, $id, ManagerRegistry $manager, EventRepository $eventRepository): Response
{
    // Action pour supprimer un événement
    $em = $manager->getManager();
    $event = $eventRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Événement non trouvé avec l\'ID '.$id);
    }

    $em->remove($event);
    $em->flush();
    
    $this->addFlash('success', 'Événement supprimé avec succès.');

    return $this->redirectToRoute('app_show_events');
}
#[Route('/event/edit/{id}', name: 'app_edit_event')]
    public function edit(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_show_events');
        }

        return $this->render('event/edit.html.twig', ['form' => $form->createView(),
        'event' => $event,]);
    }
    #[Route('/event/details/{id}', name: 'app_event_details')]
    public function showDetails(EventRepository $eventRepository, $id): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID '.$id.' n\'existe pas.');
        }

        return $this->render('event/details.html.twig', [
            'event' => $event,
        ]);
       
    }
    #[Route('/event/participer/{id}', name: 'app_participer_event')]
    public function participer(Event $event, EntityManagerInterface $entityManager): Response
    {
        // Appeler la méthode participer de l'événement
        $event->participer();

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Ajouter un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Vous avez participé à l\'événement avec succès.');

        // Rediriger vers la page des détails de l'événement
        return $this->redirectToRoute('app_event_details', ['id' => $event->getId()]);
    }
   

}
