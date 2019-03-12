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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/question/{id}", name="answers_by_question", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function AnswersByQuestion(Question $question, AnswerRepository $answerRepository, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {   
        $user = $this->getUser();
        $isQuestionAuthor = "";
        if ($user != null && $user->getId() == $question->getAuthor()->getId()) {
            $isQuestionAuthor = true;
        } else {
            $isQuestionAuthor = false;
        }

        $validatedAnswer = $question->getValidatedAnswer();
        if (true === $authChecker->isGranted('ROLE_MODO')) {
            $answers = $answerRepository->findByQuestionModo($question);
            
        } else {
            $answers = $answerRepository->findByQuestion($question);
            if ($validatedAnswer->getIsActive() != true) {
                $validatedAnswer = null;
            }
        }
        
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

            $this->addFlash(
                'info',
                'Votre réponse a été ajoutée'
            );

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

        $this->addFlash(
            'info',
            'La réponse qui vous a été utile est maitenant mise en avant'
        );

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

        $this->addFlash(
            'info',
            'Cette réponse a repris sa place initiale'
        );

        return $this->redirect('/answer/question/' . $question->getId());
    }
}
