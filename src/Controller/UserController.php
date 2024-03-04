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
use Symfony\Component\Security\Core\Security;




class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
    
        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }




    #[Route('/confirmaccount', name: 'confirm_account')]

    public function confirmAccount(): Response
    {
        // Render the template and pass any necessary variables
        return $this->render('registration/confirmaccount.html.twig', [
            // You can pass any variables needed by the template here
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
    #[Route('/profileuser', name: 'user_profile')]
    public function index(Security $security): Response
    {
        // Get the logged-in user
        $user = $security->getUser();

        // Check if a user is logged in
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access this page.');
        }

        // Get the user's email and roles
        $email = $user->getEmail();
        $roles = $user->getRoles();
        $bio = $user->getBio();


        return $this->render('user/profile.html.twig', [
            'email' => $email,
            'roles' => $roles,
            'bio'=>$bio,

        ]);
    }





}
