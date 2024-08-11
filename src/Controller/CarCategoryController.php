<?php

namespace App\Controller;

use App\Entity\CarCategory;
use App\Form\CarCategoryType;
use App\Repository\CarCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/car/category')]
class CarCategoryController extends AbstractController
{
    #[Route('/', name: 'app_car_category_index', methods: ['GET'])]
    public function index(CarCategoryRepository $carCategoryRepository): Response
    {
        return $this->render('car_category/index.html.twig', [
            'car_categories' => $carCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_car_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carCategory = new CarCategory();
        $form = $this->createForm(CarCategoryType::class, $carCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car_category/new.html.twig', [
            'car_category' => $carCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_category_show', methods: ['GET'])]
    public function show(CarCategory $carCategory): Response
    {
        return $this->render('car_category/show.html.twig', [
            'car_category' => $carCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CarCategory $carCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarCategoryType::class, $carCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_car_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car_category/edit.html.twig', [
            'car_category' => $carCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_category_delete', methods: ['POST'])]
    public function delete(Request $request, CarCategory $carCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $carCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($carCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
