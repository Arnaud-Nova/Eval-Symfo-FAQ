<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/user", name="back_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $roles = [];
        foreach ($users as $user) {
            
            $roles[] = $user->getRolesName();
        }

        // dd($roles);
        return $this->render('back/user/index.html.twig', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }


    /**
     * @Route("/to-modo/{id}", name="user_to_modo", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function changeRoleToModo(User $user): Response
    {
        $user->setRoles('{"name": "Modérateur", "code": "ROLE_MODO"}');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash(
            'info',
            'Le changement de rôle a été enregistré pour l\'utilisateur sélectionné'
        );

        return $this->redirectToRoute('back_user_index');
    }

    /**
     * @Route("/to-user/{id}", name="user_to_user", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function changeRoleToUser(User $user): Response
    {
        $user->setRoles('{"name": "Membre", "code": "ROLE_USER"}');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash(
            'info',
            'Le changement de rôle a été enregistré pour l\'utilisateur sélectionné'
        );

        return $this->redirectToRoute('back_user_index');
    }
}
