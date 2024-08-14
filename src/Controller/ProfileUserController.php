<?php

namespace App\Controller;

use App\Entity\ProfileUser;
use App\Form\ProfileUserType;
use App\Repository\ProfileUserRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user/profile')]
class ProfileUserController extends AbstractController
{
    #[Route('/', name: 'app_profile_user_index', methods: ['GET'])]
    public function index(ProfileUserRepository $profileUserRepository): Response
    {

        $profile_user = $profileUserRepository->findOneBy(['user' => $this->getUser()]);
        if ($profile_user) {

            return $this->redirectToRoute('app_profile_user_show', ['slug' => $profile_user->getSlug()]);
        } else {
            return $this->redirectToRoute('app_profile_user_new', []);
        }
        return $this->render('profile_user/index.html.twig', [
            'profile_users' => $profileUserRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profile_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface, PictureService $pictureService): Response
    {
        $profileUser = new ProfileUser();
        $form = $this->createForm(ProfileUserType::class, $profileUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request->get('profile_user')['pseudo']);
            if (!$request->get('profile_user')['pseudo']) {
                $profileUser->setPseudo($profileUser->getName() . uniqid());
            }
            //si image est envoyé, on traite l'enregistrement
            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $profileUser);
                $profileUser->setPhoto($filename);
            } else {
                $profileUser->setPhoto('profile_default.webp');
            }
            // dd($profileUser);
            $slug = strtolower($sluggerInterface->slug($profileUser->getName() . uniqid()));
            $profileUser->setUser($this->getUser())
                ->setSlug($slug);

            // dd($profileUser);
            $entityManager->persist($profileUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile_user/new.html.twig', [
            'profile_user' => $profileUser,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_profile_user_show', methods: ['GET'])]
    public function show($slug, ProfileUserRepository $profileUserRepository): Response
    {

        return $this->render('profile_user/show.html.twig', [
            'profile_user' => $profileUserRepository->findOneBy(['slug' => $slug])
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProfileUser $profileUser, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $form = $this->createForm(ProfileUserType::class, $profileUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$request->get('profile_user')['pseudo']) {
                $profileUser->setPseudo($profileUser->getName() . uniqid());
            }
            //si image est envoyé, on traite l'enregistrement
            if ($form->get('photoImage')->getData()) {
                //on recupere le nom image une fois enregister
                $filename = $pictureService->add($form, $profileUser);
                $profileUser->setPhoto($filename);
            }
            $entityManager->persist($profileUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile_user/edit.html.twig', [
            'profile_user' => $profileUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profile_user_delete', methods: ['POST'])]
    public function delete(Request $request, ProfileUser $profileUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $profileUser->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($profileUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
