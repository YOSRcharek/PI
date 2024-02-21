<?php

namespace App\Controller;
use App\Form\UserFormType;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function userList(): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/deleteUser/{id}', name: 'book_delete')]
public function deleteBook(Request $request, $id, ManagerRegistry $manager, UserRepository $userRepository): Response
{
    $em = $manager->getManager();
    $user = $userRepository->find($id);

    $em->remove($user);
    $em->flush();

    return $this->redirectToRoute('user_list');
}


#[Route('/editUser/{id}', name: 'user_edit')]
    public function editUser(Request $request, ManagerRegistry $manager, $id, UserRepository $userRepository): Response
    {
        $em = $manager->getManager();

        $user = $userRepository->find($id);
        
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_list');
        }

        return $this->renderForm('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
