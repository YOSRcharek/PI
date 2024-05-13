<?php

namespace App\Controller;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\CommentLike;
use App\Form\PostType;
use App\Form\CommentType;
use App\Form\SearchFormType;
use App\Form\ShowAllFormType as AppShowAllFormType;
use App\Repository\CommentLikeRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Security\Core\Security;

/************************************************************************************************************ */

#[Route('/post')]
class PostController extends AbstractController
{

   #[Route('/indexadmin', name: 'app_post_indexadmin', methods: ['GET'])]
    public function indexadmin(PostRepository $postRepository, Security $security): Response
    {
        $user = $security->getUser();

        
        return $this->render('post/showall.html.twig', [
            'posts' => $postRepository->findAll(),
            'username' => $user,
            
        ]);
    }
/************************************************************************************************************ */   
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(): Response
    {   

        return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        
    }
/************************************************************************************************************ */
   
    #[Route('/search', name: 'ajax_search_post', methods: ['GET'])]
    public function searchAction(Request $request,PostRepository $postRepository)
    {
      

      $requestString = $request->get('q');

      $posts =  $postRepository->findEntitiesByString($requestString);

      if(!$posts) {
          $result['posts']['error'] = "No posts found matching your search";
      } else {
          $result['posts'] = $this->getRealPosts($posts);
      }

      return new Response(json_encode($result));
    }

    public function getRealPosts($posts){

        $realposts = array();

        foreach ($posts as $post){
            $realposts[$post->getId()] = array(
                'id' => $post->getId(),
                'createdat' => $post->getCreatedAt()->format('Y-m-d'),
                'image' => $post->getImage(),
                'title' => $post->getTitle()
                
            );
        }
      
        return $realposts;
    }


/************************************************************************************************************ */
#[Route('/showall/{page?1}/{sort?date_desc}', name: 'app_show_all', methods: ['GET', 'POST'],
requirements: ['page' => '\d+', 'sort' => '(date_asc|date_desc|title_asc|title_desc|rating_desc|comments_desc)'])]
public function showall(PostRepository $postRepository, $page, $sort, Request $request): Response
{
    $page = $request->attributes->getInt('page', 1);

    $showAllForm = $this->createForm(AppShowAllFormType::class);
    $showAllForm->handleRequest($request);

    if ($showAllForm->isSubmitted() && $showAllForm->isValid()) {
        $nbr = $showAllForm->getData()['nbr'];
        $page = 1;
    } else {
        $nbr = 10;
    }

    $sort = $request->query->get('sort', 'date_desc');
    $orderBy = $this->getOrderBy($sort);

    $searchForm = $this->createForm(SearchFormType::class);
    $searchForm->handleRequest($request);

    // Retrieve main posts
    if ($searchForm->isSubmitted() && $searchForm->isValid()) {
        $searchQuery = $searchForm->getData()['search'];
        $posts = $postRepository->search($searchQuery);
        $nbrPosts = count($posts);
    } else {
        $posts = $postRepository->findBy(['visible' => true], $orderBy, $nbr, ($page - 1) * $nbr);
        $nbrPosts = $postRepository->count(['visible' => true]);
    }

    $nbrPages = ceil($nbrPosts / $nbr);

    if ($page > $nbrPages || $page < 1) {
        $page = 1;
    }

    // Retrieve newest posts for sidebar
    $newestPostsSidebar = $postRepository->findBy(['visible' => true], ['createdat' => 'DESC'], 4);

    return $this->render('post/show_all.html.twig', [
        'posts' => $posts,
        'isPaginated' => $nbrPosts >= 10,
        'nbrPages' => $nbrPages,
        'currentPage' => $page,
        'nbr' => $nbr,
        'sort' => $sort,
        'showAllForm' => $showAllForm->createView(),
        'searchForm' => $searchForm->createView(),
        'newestPostsSidebar' => $newestPostsSidebar, // Newest posts for sidebar
    ]);
}

private function getOrderBy(string $sort): array
{
    switch ($sort) {
        case 'date_asc':
            return ['createdat' => 'ASC'];
        case 'title_asc':
            return ['title' => 'ASC'];
        case 'title_desc':
            return ['title' => 'DESC'];
        case 'rating_desc':
            return ['rating' => 'DESC'];
        case 'comments_desc':
            return ['comments' => 'DESC'];
        default:
            return ['createdat' => 'DESC'];
    }
}
/************************************************************************************************************ */
    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, Security $security): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedat(new DateTime());
            $post->setRating(1);
            $post->setUsername_id(1);

            $post->setVisible(true);
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }








 /****************************************************************************************************/
    //like comment
    #[Route('/comment/{id}/like', name: 'comment_like', methods: ['GET', 'POST'])]
    public function like(Comment $comment, ObjectManager $manager, CommentLikeRepository $likeRepo, Security $security): Response
    {
        $user = $security->getUser();
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);
        if($comment->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'comment' => $comment,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'like removed',
                'likes' => $likeRepo->count(['comment' => $comment])
            ], 200);
        }
        $like = new CommentLike();
        $like->setComment($comment)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'like added',
            'likes' => $likeRepo->count(['comment' => $comment])
        ], 200); 
        
    }
/************************************************************************************************************ */
    #[Route('/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Post $post,Security $security, CommentRepository $CommentRepository, HttpClientInterface $httpClient): Response
    {
        $comment= new Comment();
        $commentform = $this->createForm(CommentType::class, $comment);
        
        $commentform->handleRequest($request);
        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comment->setIDPost($post);
            $comment->setUpvotes(0);
            $comment->setCreatedatcomment(new DateTime());
            $comment->setUsername($security->getUser());
            //filter for bad words:
                $content = $comment->getContentcomment();
                $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                    'query' => [
                        'content' => $content
                    ],
                    'headers' => [
                        'User-ID' => '007007',
                        'API-Key' => 'SDP4TUoFlxnmnSHz6kTHBD33OOwgMOO4aWwiE1eaL9MiQ6Aw',
                    ]
                ]);
        
                if ($response->getStatusCode() === 200) {
                    $result = $response->toArray();
                    if ($result['is-bad']) {
                        // Handle bad word found
                        $this->addFlash('danger', '</i>Your comment contains inappropriate language and cannot be posted.');
                        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
                    } else {
                        // Save comment
                        $this->addFlash('success', 'Your comment has been posted.');

                        $CommentRepository->save($comment, true);
                        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    // Handle API error
                    
                    return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        if($post->isVisible() == false){
            return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $CommentRepository->findBy(['IDPost' => $post->getId()]),
            'comment_form' => $commentform->createView(),
            'user' => $security->getUser() 
        ]);

    }













/************************************************************************************************************ */
    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            
            'form' => $form,
        ]);
    }


    /************************************************************************************************************ */
    #[Route('/post/{id}/rate', name: 'post_rate', methods: ['POST'])]
    public function rate(Request $request, Post $post, ObjectManager $manager, Security $security): Response
    {
        // Your rate() method logic goes here
          // Retrieve the rating value from the request
    $ratingValue = $request->request->get('rating');

    // Update the rating column of the Post entity
    $post->setRating($ratingValue);

    // Persist and flush the changes to the database
    $manager->persist($post);
    $manager->flush();

    // Return a JSON response indicating success
    return $this->json([
        'message' => 'Rating saved successfully'
    ], 200);
    }
    
}
