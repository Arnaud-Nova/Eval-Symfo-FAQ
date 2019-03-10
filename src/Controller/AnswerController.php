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
     * @Route("/question/{id}", name="answers_by_question", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function AnswersByQuestion(Question $question, AnswerRepository $answerRepository, Request $request): Response
    {   
        $user = $this->getUser();
        $isQuestionAuthor = "";
        if ($user != null && $user->getId() == $question->getAuthor()->getId()) {
            $isQuestionAuthor = true;
        } else {
            $isQuestionAuthor = false;
        }
        $answers = $answerRepository->findByQuestion($question);
        $validatedAnswer = $question->getValidatedAnswer();
        // dd($validatedAnswer);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
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
            'isQuestionAuthor' => $isQuestionAuthor,
            'validatedAnswer' => $validatedAnswer
        ]);
    }

    /**
     * @Route("/{answerId}/validate/question/{id}", name="validate_answer_for_question", methods={"GET"}, requirements={"id"="\d+", "answerId"="\d+"})
     */
    public function validateAnswerForQuestion($answerId, Question $question, AnswerRepository $answerRepository): Response
    {
        $validatedAnswer = $answerRepository->find($answerId);
        $entityManager = $this->getDoctrine()->getManager();
        $question->setValidatedAnswer($validatedAnswer);
        $entityManager->persist($question);
        $entityManager->flush();

        return $this->redirect('/answer/question/' . $question->getId());
    }

    /**
     * @Route("/unvalidate/question/{id}", name="unValidate_answer_for_question", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function unValidateAnswerForQuestion(Question $question): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $question->setValidatedAnswer(null);
        $entityManager->persist($question);
        $entityManager->flush();

        return $this->redirect('/answer/question/' . $question->getId());
    }
}
