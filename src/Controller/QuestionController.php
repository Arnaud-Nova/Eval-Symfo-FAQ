<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;
use App\Entity\Question;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(QuestionRepository $QuestionRepo)
    {
        $questions = $QuestionRepo->questionsByCreatedAtDesc();

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
}
