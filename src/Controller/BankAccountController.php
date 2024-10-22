<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('user/bank/account')]
class BankAccountController extends AbstractController
{
    #[Route('/', name: 'app_bank_account_index', methods: ['GET'])]
    public function index(BankAccountRepository $bankAccountRepository): Response
    {
        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $bankAccountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bank_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bankAccount = new BankAccount();
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bankAccount);
            $entityManager->flush();

            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank_account/new.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_account_show', methods: ['GET'])]
    public function show(BankAccount $bankAccount): Response
    {
        return $this->render('bank_account/show.html.twig', [
            'bank_account' => $bankAccount,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bank_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BankAccount $bankAccount, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_account_delete', methods: ['POST'])]
    public function delete(Request $request, BankAccount $bankAccount, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bankAccount->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bankAccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
    }
}