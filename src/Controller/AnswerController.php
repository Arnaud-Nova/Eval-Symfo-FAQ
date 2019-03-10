<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/question/{id}", name="answers_by_question", methods={"GET", "POST"})
     */
    public function AnswersByQuestion(Question $question, AnswerRepository $answerRepository, Request $request): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $answers = $answerRepository->findByQuestion($question);
        
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        // dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $requestUri = $request->getRequestUri();
            
            $entityManager = $this->getDoctrine()->getManager();
            $answer->setAuthor($user);
            $answer->setQuestion($question);
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirect($requestUri);
        }

        return $this->render('question/show.html.twig', [
            'answers' => $answers,
            'question' => $question,
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }
}
