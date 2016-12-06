<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Entity\QuestionReport;
use AppBundle\Form\QuestionReportType;
use AppBundle\Form\QuestionType;
use AppBundle\Repository\QuestionReportRepository;
use AppBundle\Repository\QuestionRepository;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends Controller
{
    /**
     * @Route("/add-question", name="addQuestion")
     * @Security("has_role('ROLE_USER')")
     */
    public function addQuestionAction(Request $request)
    {
        $question = new Question();
        $question->prepareAnswerFields(4);
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setCreatedBy($this->getUser())
                ->setCheckboxAnswers(false)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->isCheckboxType();

            $em = $this->getDoctrine()->getManager();

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Your question has been submitted for review!');
            return new RedirectResponse('/');
        }
        return $this->render('@App/Questions/createQuestion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/my-questions/{page}", name="myQuestions")
     * @Security("has_role('ROLE_USER')")
     */
    public function myQuestionsAction(Request $request, $page = 1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');

        $questions  = $repository->getPaginatedQuestions($page, $this->getUser()->getId());
        $maxPages   = ceil($questions->count() / QuestionRepository::MAX_RESULTS);


        return $this->render('@App/Questions/myQuestions.html.twig', [
            'questions' => $questions,
            'maxPages'  => $maxPages,
            'thisPage'  => $page
        ]);
    }

    /**
     * @Route("/delete", name="delete")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repository = $request->request->get('repository');
            $id         = $request->get('id');

            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:' . $repository)
                ->findOneBy(['id' => $id, 'created_by' => $this->getUser()->getId()]);
            $response = new JsonResponse();

            if ($entity) {
                $em->remove($entity);
                $em->flush();

                $response->setStatusCode(200, 'success');
            } else {
                $response->setStatusCode(400, 'error');
            }
            return $response;
        }
        return new Response();
    }

    /**
     * @Route("/edit-question/{id}-{slug}", name="editQuestion")
     * @Security("has_role('ROLE_USER')")
     */
    public function editQuestionAction(Request $request, $id)
    {
        $em         = $this->getDoctrine()->getManager();
        $question   = $em->getRepository('AppBundle:Question')
            ->findOneBy(['id' => $id, 'created_by' => $this->getUser()->getId()]);

        if ($question) {
            $form = $this->createForm(QuestionType::class, $question);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var PersistentCollection $answers */
                $answers = $form['answers']->getData();

                $question->setUpdatedAt(new \DateTime())
                    ->setAnswers($answers->map(function ($answer) {return $answer; }))
                    ->isCheckboxType()
                    ->updateAnswers();

                $em->getRepository('AppBundle:Answer')->removeAnswers($question->getId(), $question->getAnswers());
                $em->flush();

                $this->addFlash('success', 'Your question has been updated!');
                return new RedirectResponse('/my-questions');
            }
            return $this->render('@App/Questions/editQuestion.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('AppBundle:Home:404.html.twig');
    }

    /**
     * @Route("/my-reports/{page}", name="myReports")
     * @Security("has_role('ROLE_USER')")
     */
    public function myReportsAction(Request $request, $page = 1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:QuestionReport');

        $reports    = $repository->getPaginatedReports($page, $this->getUser()->getId());
        $maxPages   = ceil($reports->count() / QuestionReportRepository::MAX_RESULTS);


        return $this->render('@App/Questions/myReports.html.twig', [
            'reports'   => $reports,
            'maxPages'  => $maxPages,
            'thisPage'  => $page
        ]);
    }

    /**
     * @Route("/edit-report/{id}", name="editReport")
     * @Security("has_role('ROLE_USER')")
     */
    public function editReportAction(Request $request, $id)
    {
        $em     = $this->getDoctrine()->getManager();
        $report = $em->getRepository('AppBundle:QuestionReport')
            ->findOneBy(['id' => $id, 'created_by' => $this->getUser()->getId()]);

        if ($report) {
            $form = $this->createForm(QuestionReportType::class, $report);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $report->setUpdatedAt(new \DateTime());
                $em->flush();

                $this->addFlash('success', 'Your report has been updated!');
                return new RedirectResponse('/my-reports');
            }
            return $this->render('@App/Questions/editReport.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('AppBundle:Home:404.html.twig');
    }

}
