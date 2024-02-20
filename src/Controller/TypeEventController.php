<?php

namespace App\Controller;

use App\Entity\TypeEvent;
use App\Form\TypeEventType;
use App\Repository\TypeEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TypeEventController extends AbstractController
{
    #[Route('/types', name: 'app_type_event_index', methods: ['GET'])]
    public function showall(TypeEventRepository $typeEventRepository): Response
    {
        return $this->render('type_event/index.html.twig', [
            'type_events' => $typeEventRepository->findAll(),
        ]);
    }

    #[Route('/type_event/new', name: 'app_type_event_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeEvent = new TypeEvent();
        $form = $this->createForm(TypeEventType::class, $typeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeEvent);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_event/new.html.twig', [
            'type_event' => $typeEvent,
            'form' => $form,
        ]);
    }

    #[Route('/type_event/{id}', name: 'app_type_event_show')]
    public function show(TypeEvent $typeEvent): Response
    {
        return $this->render('type_event/show.html.twig', [
            'type_event' => $typeEvent,
        ]);
    }

    #[Route('/type_event/edit/{id}', name: 'app_type_event_edit')]
    public function edit(Request $request, TypeEvent $typeEvent, EntityManagerInterface $entityManager): Response
    {
         $form = $this->createForm(TypeEventType::class, $typeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_event/edit.html.twig', [
            'type_event' => $typeEvent,
            'form' => $form,
        ]);
    }

    #[Route('/type_event/delete/{id}', name: 'app_type_event_delete')]
    public function delete(Request $request, TypeEvent $typeEvent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeEvent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
