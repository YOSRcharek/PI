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
use App\Repository\TypeEventRepository;
use App\Entity\TypeEvent;
use App\Form\TypeEventType;

class AdminEventController extends AbstractController
{
    #[Route('/admin/events', name: 'app_show_eventsadmin')]
    public function showEventsadmin(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('admin\show.html.twig', ['event' => $events]);
    }
    #[Route('/admin/event/create', name: 'app_create_eventadmin')]
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
                return $this->redirectToRoute('app_show_eventsadmin');
            }
        } catch (\Exception $e) {
            
            $this->addFlash('error', 'Une erreur s\'est produite lors de la création de l\'événement.');
        }
        return $this->render('admin\create_events.html.twig', ['form' => $form->createView()]);    
    }
    #[Route('/admin/event/details/{id}', name: 'app_event_detailsadmin')]
    public function showDetailsadmin(EventRepository $eventRepository, $id): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID '.$id.' n\'existe pas.');
        }

        return $this->render('admin\showdetailsadmin.html.twig', [
            'event' => $event,
        ]);
       
    }
    #[Route('/admin/event/participer/{id}', name: 'app_participer_eventadmin')]
    public function participer(Event $event, EntityManagerInterface $entityManager): Response
    {
        // Appeler la méthode participer de l'événement
        $event->participer();

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Ajouter un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Vous avez participé à l\'événement avec succès.');

        // Rediriger vers la page des détails de l'événement
        return $this->redirectToRoute('app_event_detailsadmin', ['id' => $event->getId()]);
    }
    #[Route('admin/event/delete/{id}', name: 'app_delete_eventadmin')]
public function deleteEventadmin(Request $request, $id, ManagerRegistry $manager, EventRepository $eventRepository): Response
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

    return $this->redirectToRoute('app_show_eventsadmin');
}
#[Route('admin/type_event/new', name: 'app_type_event_newadmin')]
public function newtypeadmin(Request $request, EntityManagerInterface $entityManager): Response
{
    $typeEvent = new TypeEvent();
    $form = $this->createForm(TypeEventType::class, $typeEvent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($typeEvent);
        $entityManager->flush();

        return $this->redirectToRoute('app_type_event_indexadmin');
    }

    return $this->renderForm('admin\ceate_type.html.twig', [
        'type_event' => $typeEvent,
        'form' => $form,
    ]);
}
    #[Route('admin/types', name: 'app_type_event_indexadmin')]
    public function showall(TypeEventRepository $typeEventRepository): Response
    {
        return $this->render('admin\showalltypeadmin.html.twig', [
            'type_events' => $typeEventRepository->findAll(),
        ]);
    }
    #[Route('admin/event/edit/{id}', name: 'app_edit_eventadmin')]
    public function editadmin(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, int $id): Response
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

        return $this->render('admin\editadmin.html.twig', ['form' => $form->createView(),
        'event' => $event,]);
    }
}
