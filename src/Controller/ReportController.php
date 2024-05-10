<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\PostRepository;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/report')]
class ReportController extends AbstractController
{
    #[Route('/', name: 'app_report_index', methods: ['GET'])]
    public function index(ReportRepository $reportRepository): Response
    {
        return $this->render('report/index.html.twig', [
            'reports' => $reportRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReportRepository $reportRepository,PostRepository $postRepository, Security $security,$id): Response
    {
        $post = $postRepository->find($id);
        $report = new Report();
        $report->setReportedPost($post);
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setUsername($security->getUser());
            $reportRepository->save($report, true);

            
            $post = $report->getReportedPost();

            return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
}


        return $this->renderForm('report/new.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_report_show', methods: ['GET'])]
    public function show(Report $report,PostRepository $postRepository): Response
    {
        
        return $this->render('report/sho.html.twig', [
            
            'report' => $report
        ]);
    }

    #[Route('/{id}/edit', name: 'app_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Report $report, ReportRepository $reportRepository): Response
    {
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportRepository->save($report, true);

            return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('report/edit.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_report_delete', methods: ['POST'])]
    public function delete(Request $request, Report $report, ReportRepository $reportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $reportRepository->remove($report, true);
        }

        return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
    }
}
