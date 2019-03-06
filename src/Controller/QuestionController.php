<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(QuestionRepository $QuestionRepo)
    {
        $questions = $QuestionRepo->findAll();

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
