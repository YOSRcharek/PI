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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;






class EventController extends AbstractController
{
  
    #[Route('/events', name: 'app_show_events')]
    public function showEvents(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Retrieve all events
        $events = $eventRepository->findAll();

        // Paginate the results
        $pagination = $paginator->paginate(
            $events,
            $request->query->getInt('page', 1), // Get the current page from the request
            3 // Number of items per page
        );

        return $this->render('event/showAllevents.twig', ['pagination' => $pagination]);
    }
    #[Route('/event/create', name: 'app_create_event')]
    public function createEvent(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
    
        $form->handleRequest($request);
    
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $imageFile = $form->get('image')->getData();
    
                if ($imageFile instanceof UploadedFile) {
                    $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
    
                    try {
                        $imageFile->move(
                            $this->getParameter('your_image_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new \Exception('An error occurred during image upload.');
                    }
    
                    $event->setImage($newFilename);
                }
    
                $entityManager->persist($event);
                $entityManager->flush();
    
                $this->addFlash('success', 'L\'événement a été créé avec succès !');
                return $this->redirectToRoute('app_show_events');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de la création de l\'événement: ' . $e->getMessage());
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
        $event->participer();

       
        $entityManager->flush();

        
        $this->addFlash('success', 'Vous avez participé à l\'événement avec succès.');

        
        return $this->redirectToRoute('app_event_details', ['id' => $event->getId()]);
    }
    

    #[Route('/event/search', name: 'app_search_event')]
    public function searchEvent(EventRepository $eventRepository, Request $request): Response
    {
        try {
            $searchTerm = $request->query->get('search');
            $searchType = $request->query->get('searchType');
    
            // Initialiser les résultats de recherche à null
            $search = $searchTerm ?? null;
    
            // Utiliser une logique conditionnelle pour déterminer le type de recherche
            if ($searchType === 'name') {
                $events = $eventRepository->findByNomEvent($searchTerm);
            } elseif ($searchType === 'location') {
                $events = $eventRepository->findEventsByLocation($searchTerm);
            
            } elseif ($searchType === 'eventType') {
                // Utilisez la méthode du repository pour trouver par type d'événement
                $events = $eventRepository->findEventsByEventType($searchTerm);
            } else {
                // Logique par défaut si aucun type de recherche n'est spécifié
                $this->addFlash('warning', 'Veuillez spécifier un type de recherche.');
            }
    
            return $this->render('event/search.html.twig', ['events' => $events, 'search' => $searchTerm]);
        } catch (\Exception $e) {
            // Log l'erreur si nécessaire
            $this->addFlash('error', 'Une erreur s\'est produite.');
            // Renvoyer une réponse vide en cas d'erreur
            return $this->render('event/search.html.twig', ['events' => []]);
        }
    }
    #[Route('/search_eventajax', name: 'search_eventajax')]
    public function searchEventajax(Request $request, EventRepository $eventRepository): JsonResponse
    {
        $query = $request->query->get('query');

        // Perform search query using Doctrine ORM
        $results = $eventRepository->createQueryBuilder('e')
            ->where('e.nomEvent LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        // Transform results to array to prepare for JSON response
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'id' => $result->getId(),
                'nomEvent' => $result->getNomEvent(),
                'description' => $result->getDescription(),
                'date' => $result->getDateDebut()->format('Y-m-d H:i:s'),
                'dateFin' => $result->getDateFin()->format('Y-m-d H:i:s'),
                'type' => $result->getType()->getNom(),
               
                'localisation' => $result->getLocalisation(),
            ];
        }

        if (!empty($formattedResults)) {
            $response = ['results' => $formattedResults, 'message' => 'Résultats trouvés.'];
        } else {
            $response = ['results' => [], 'message' => 'Aucun résultat trouvé.'];
        }
        return new JsonResponse($response);
        
    }
    
    
}
