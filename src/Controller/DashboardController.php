<?php

namespace App\Controller;
use App\Repository\PostRepository;

use App\Entity\Post;
use App\Entity\Comment;

use App\Repository\CommentRepository;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('/backend/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    


    
    #[Route('/toggle_visibility/{id}/{back?app_dashboard_poste}', name: 'app_toggle_visibility', methods: ['POST'])]
    public function toggleVisibility(Post $post,$back): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post->setVisible(!$post->isVisible());
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->redirectToRoute($back, [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/reportback', name: 'app_dashboard_report', methods: ['GET'])]
    public function report(ReportRepository $reportRepository): Response
    {
        return $this->render('backend/rep.html.twig', [
            'reports' => $reportRepository->findAll(),
        ]);
    }



    
    #[Route('/postback', name: 'app_dashboard_post', methods: ['GET'])]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('backend/postback.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);

    }


    #[Route('/jareb', name: 'app_dashboard_poste', methods: ['GET'])]
    public function post(PostRepository $postRepository): Response
    {
        return $this->render('Admin/demandes.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);

    }



    #[Route('/commentback',name:'app_show_comment')]

    public function showPostComments(PostRepository $postRepository, CommentRepository $CommentRepository): Response
    {
        $posts = $postRepository->findAllWithComments();
        $comments = $CommentRepository->findAll();   
        return $this->render('backend/commentback.html.twig', [
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }




    #[Route('/posts/{id}/comments', name:'post_comments')]
 public function showPostComment(Post $post): Response
 {
     $comments = $post->getComments();
     
     return $this->render('backend/post_comments.html.twig', [
         'post' => $post,
         'comments' => $comments,
     ]);
 }
 


}

