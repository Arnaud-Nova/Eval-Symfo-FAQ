<?php

namespace App\Controller\Back;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/question", name="back_")
 */
class QuestionController extends AbstractController
{
    
    /**
     * @Route("/{id}/activation", name="activation_question", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function activationQuestion(Question $question): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($question->getIsActive() == true) {
            $question->setIsActive(false);
        } else {
            $question->setIsActive(true);
        }
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
