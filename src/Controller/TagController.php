<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/tag", name="tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, TagRepository $tagRepository):Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/questions", name="questions", requirements={"id"="\d+"})
     */
    public function questionsByTag(Tag $tag, TagRepository $tagRepository, AuthorizationCheckerInterface $authChecker):Response
    {
        if (true === $authChecker->isGranted('ROLE_MODO')) {
            $questions = $tagRepository->findQuestionsByTagModo($tag);
        } else {
            $questions = $tagRepository->findQuestionsByTag($tag);
        }
        $questions = $tagRepository->findQuestionsByTag($tag);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
