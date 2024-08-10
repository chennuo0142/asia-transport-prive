<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/user_index', name: 'app_admin_user_index')]
    public function user_index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->render('admin/user_index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/user_edit/{id}', name: 'app_admin_user_edit')]
    public function user_deit(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('app_admin_user_index');
        }
        return $this->render('admin/user_edit.html.twig', [
            'form' => $form

        ]);
    }
}
