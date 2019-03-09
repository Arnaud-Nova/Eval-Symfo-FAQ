<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Entity\Answer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/question/{id}", name="answers_by_question", methods={"GET"})
     */
    public function indexByMovie(Question $question, AnswerRepository $answerRepository): Response
    {   
        $answers = $answerRepository->findByQuestion($question);
       
        return $this->render('question/show.html.twig', [
            'answers' => $answers,
            'question' => $question
        ]);
    }
}
