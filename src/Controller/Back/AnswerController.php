<?php

namespace App\Controller\Back;

use App\Entity\Answer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/answer", name="back_")
 */
class AnswerController extends AbstractController
{

    /**
     * @Route("/answer/{id}/activation", name="activation_answer", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function activationAnswer(Answer $answer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($answer->getIsActive() == true) {
            $answer->setIsActive(false);
            $this->addFlash(
                'info',
                'Cette réponse ne sera plus visible par les membres et les visiteurs'
            );
        } else {
            $answer->setIsActive(true);
            $this->addFlash(
                'info',
                'Cette réponse sera à nouveau visible par les membres et les visiteurs'
            );
        }
        
        $entityManager->flush();

        return $this->redirect('/answer/question/' . $answer->getQuestion()->getId());
    }
}
