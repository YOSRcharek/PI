<?php

namespace App\Controller;

use App\Entity\Association;  // Ajout de l'import pour la classe Association
use App\Entity\Projet;  // Ajout de l'import pour la classe Association
use App\Entity\Membre;  // Ajout de l'import pour la classe Association
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AssociationType;
use App\Form\ProjetType;
use App\Form\MembreType;
use App\Form\AssoType;
use App\Repository\AssociationRepository;
use App\Repository\ProjetRepository;
use App\Repository\MembreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Base64EncodeService;
use App\Service\FileReadService;
use App\Service\MailerTraitement;
use Symfony\Component\Mailer\MailerInterface;
use App\Twig\Base64EncodeExtensionService;
use Symfony\Component\HttpFoundation\File\UploadedFile;








class AssociationController extends AbstractController
{
    private $base64EncodeExtensionService;

public function __construct(Base64EncodeExtensionService $base64EncodeExtensionService)
{
    $this->base64EncodeExtensionService = $base64EncodeExtensionService;
}
    #[Route('/association', name: 'app_show')]
    
    public function index(): Response
    {
        return $this->render('association/index.html.twig', [
            'controller_name' => 'AssociationController',
        ]);
    }
    #[Route('/associations', name: 'app_show_association')]
    public function show(AssociationRepository $associationRepo): Response
    {
        $associations = $associationRepo->findByStatus(1);
        return $this->render('admin/associations.html.twig', ['associations' => $associations]);
    }
    #[Route('/demandes', name: 'app_demandes')]
    public function demandes(AssociationRepository $associationRepo): Response
    { 
        $demandes = $associationRepo->findByStatus(0);
        $demandesWithDocuments = [];

          foreach($demandes as $demande){
            $documentContent =$this->getDocumentContent($demande->getDocument());
              $demandesWithDocuments[]=[
                'demande'=>$demande,
                'documentContent'=>$documentContent
              ];
              
          }
        
         return $this->render('admin/demandes.html.twig', ['demandes' => $demandesWithDocuments
         ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('app_show');
        }

        return $this->render('association/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/editAssoc/{id}', name: 'app_edit_assoc')]
    public function edit(Request $request, EntityManagerInterface $entityManager, AssociationRepository $associationRepo, int $id): Response
    {
        $association = $associationRepo->find($id);

        if (!$association) {
            throw $this->createNotFoundException('Association not found');
        }

        $form = $this->createForm(AssoType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager->flush();

            return $this->redirectToRoute('app_show');
        }

        return $this->render('association/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('detail/{id}', name: 'app_details')]
    public function showDetails(AssociationRepository $AssociationRepo, $id): Response
    {
        return $this->render('association/details.html.twig', [
            'association' => $AssociationRepo->find($id),
        ]);
    }
   
    


    #[Route('/delete/{id}', name: 'app_delete')]
public function delete(Request $request, $id, ManagerRegistry $manager, AssociationRepository $associationRepo): Response
{
    // Récupérer l'EntityManager
    $em = $manager->getManager();

    // Récupérer l'association à supprimer
    $association = $associationRepo->find($id);

    // Vérifier si l'association existe
    if (!$association) {
        throw $this->createNotFoundException('Association not found');
    }


    // Récupérer tous les membres liés à l'association
    $membres = $association->getMembres();

    // Supprimer tous les membres liés
    foreach ($membres as $membre) {
        $em->remove($membre);
    }

    // Récupérer tous les projets liés à l'association
    $projets = $association->getProjets();

    // Supprimer tous les projets liés
    foreach ($projets as $projet) {
        $em->remove($projet);
    }

    // Supprimer l'association elle-même
    $em->remove($association);

    // Appliquer les modifications à la base de données
    $em->flush();

    return $this->redirectToRoute('app_show');
}
    #[Route('/createAcc', name: 'app_inscrire')]
    
public function inscrire(ManagerRegistry $managerRegistry, Request $request, MailerTraitement $service): Response
{
    // Action to create a new association
    $association = new Association();

    $form = $this->createForm(AssociationType::class, $association);

    // Handle form submission
    $form->handleRequest($request);

    try {
        // Begin the transaction
        $entityManager = $managerRegistry->getManager();
        $entityManager->beginTransaction();

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the uploaded file
            /** @var UploadedFile $documentFile */
            $documentFile = $form->get('document')->getData();
       
            if ($documentFile) {
                $documentContent = file_get_contents($documentFile->getPathname());

                $association->setDocument($documentContent);
            }

            $entityManager->persist($association);
            $entityManager->flush();

            // Get the email entered in the form
            $email = $form->get('email')->getData();

            // Send the email
            $service->sendEmail($email);

            $entityManager->commit();

            return $this->redirectToRoute('app_home');
        }

    } catch (\Exception $e) {
        $entityManager->rollback();
        throw $e;

    }

    return $this->render('home/create-account.html.twig', ['form' => $form->createView()]);
}



    #[Route('/dessapprouver/{id}', name: 'app_desapp')]
    public function desapprouver(Request $request, $id, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $association = $entityManager->getRepository(Association::class)->find($id);

        // Vérifier si l'entité est trouvée
        if ($association) {
            $entityManager->remove($association);
            $entityManager->flush();
        } else {
            // Gérer le cas où l'entité n'est pas trouvée
            throw $this->createNotFoundException('L\'association avec l\'identifiant ' . $id . ' n\'a pas été trouvée.');
        }

        return $this->redirectToRoute('app_demandes'); // Modifié pour utiliser le nom de la route correct
    }

    #[Route('/approuver/{id}', name: 'app_approuver')]
public function approuver($id, ManagerRegistry $managerRegistry, Request $request, MailerInterface $mailer, MailerTraitement $service): Response
{
    $entityManager = $this->getDoctrine()->getManager();
        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException('Association non trouvée avec l\'identifiant ' . $id);
        }

        // Mettre à jour le champ status
        $association->setStatus(true);
        // Envoi de l'email au demandeur pour activer son compte
        $token = $this->generateToken(); // Générer un token unique
        $email = $association->getEmail(); // Supposant que l'email est un attribut de l'association
        $service->sendActivationEmail($mailer, $email, $token); // Appel à la fonction pour envoyer l'email
        $entityManager->flush();

        return $this->redirectToRoute('app_show'); // Redirigez où vous le souhaitez après la mise à jour
    
}
#[Route('/profil/{id}', name: 'app_profil')]
public function profil(Request $request, AssociationRepository $associationRepo, EntityManagerInterface $entityManager, ProjetRepository $projetRepo, MembreRepository $membreRepo, $id): Response
{    
    $entityManager = $this->getDoctrine()->getManager();
    $association = $entityManager->getRepository(Association::class)->find($id);

    if (!$association) {
        throw $this->createNotFoundException('Association non trouvée avec l\'identifiant ' . $id);
    }

    $projets = $projetRepo->findByAssociation($id);
    $membres = $membreRepo->findByAssociation($id);
    $membre = new Membre();
    $projet = new Projet();
    $form = $this->createForm(MembreType::class, $membre);
    $form2 = $this->createForm(ProjetType::class, $projet);

    $ongoingProjectsCount = $projetRepo->countOngoingProjects($id);
    $completedProjectsCount = $projetRepo->countCompletedProjects($id);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $membre->setAssociation($association);
        $entityManager->persist($membre);
        $entityManager->flush();

        return $this->redirectToRoute('app_profil', ['id' => $id]);

    }

     $form2->handleRequest($request);
    if ($form2->isSubmitted() && $form2->isValid()) {

        $projet->setAssociation($association);
        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('app_profil', ['id' => $id]);
         
    }
    
    return $this->render('association/profile.html.twig', [
        'association' => $association,
        'projets' => $projets,
        'membres'=> $membres,
        'form' => $form->createView(),
        'form2' => $form2->createView(),
        'ongoingProjectsCount' => $ongoingProjectsCount,
        'completedProjectsCount' => $completedProjectsCount,
    ]);
            
     
}


private function getDocumentContent($document)
{
    return $document ? $this->base64EncodeExtensionService->readfile($document) : null;
}
private function generateToken(): string
{
    return bin2hex(random_bytes(32)); // Example: Generate a random hexadecimal string
}
public function findByStatus($status): array
{
    return $this->getDoctrine()->getRepository(Association::class)->findBy(['status' => $status]);
}
public function findByAssociation($associationId): array
{
    $qb = $this->createQueryBuilder('p');
    $qb->join('p.association', 'a')
       ->andWhere('a.id = :associationId')
       ->setParameter('associationId', $associationId);
    $projects = $qb->getQuery()->getResult();
    return $projects;
}



}