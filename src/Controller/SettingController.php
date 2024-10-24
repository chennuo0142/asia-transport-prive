<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion/parameter/setting')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'app_setting')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $setting = $this->getUser()->getSetting();
        dump($setting);
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($setting);
            $entityManager->flush();

            $this->addFlash('success', 'Tous les donnes sont sauvegarder');
        }

        return $this->render('setting/index.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }
}
