<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(QuestionRepository $QuestionRepo, AuthorizationCheckerInterface $authChecker)
    {
        if (true === $authChecker->isGranted('ROLE_MODO')) {
            $questions = $QuestionRepo->questionsByCreatedAtDescModo();
        } else {
            $questions = $QuestionRepo->questionsByCreatedAtDesc();
        }

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/question/{id}", name="show_question", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Question $question)
    {
        // dd($question);
        if (!$question) {
            throw $this->createNotFoundException('Cette question est introuvable');
        }

        
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/question/new", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $question->setAuthor($user);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
}
