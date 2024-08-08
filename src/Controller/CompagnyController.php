<?php

namespace App\Controller;

use App\Entity\Compagny;
use App\Form\CompagnyType;
use App\Repository\CompagnyRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/gestion/compagny')]
class CompagnyController extends AbstractController
{
    #[Route('/', name: 'app_compagny_index', methods: ['GET'])]
    public function index(CompagnyRepository $compagnyRepository): Response
    {

        return $this->render('compagny/index.html.twig', [
            'compagny' => $compagnyRepository->findOneBy(['user' => $this->getUser()]),
        ]);
    }
    public function calcul_numeros_tva_fr(int $siret): string
    {
        //formule de calcule:  Clé TVA = [12 + 3 x (SIREB modulo 97)] modulo 97
        //12 + 3 x (SIREN modulo 97 modulo 97) modulo 97
        //siret asia transport prive : 844 922 369
        // resultat : FR 76 844922369
        $cle_tva = (12 + 3 * ($siret % 97)) % 97;
        dd($cle_tva);
        $num_tva = "FR " . $cle_tva . $siret;
        return $num_tva;
    }

    #[Route('/new', name: 'app_compagny_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $compagny = new Compagny();
        $num_tva = "";

        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = strtolower($sluggerInterface->slug($compagny->getName() . uniqid()));

            $code = $request->get('compagny')['compagnyId'];
            //supprime les espaces
            $data = str_replace(" ", "", $code);

            //on selectionne les 9 premier lettres
            $siren = substr($data, 0, 9);

            //on convertie le sting en int
            $siret = intval($siren);

            if ($siret) {
                $cle_tva = (12 + 3 * ($siret % 97)) % 97;

                $num_tva = "FR " . $cle_tva . $siret;
            }

            $compagny->setUser($this->getUser())
                ->setTvaId($num_tva)
                ->setSlug($slug)
                ->setCreateAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable());
            $entityManager->persist($compagny);
            $entityManager->flush();

            return $this->redirectToRoute('app_compagny_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compagny/new.html.twig', [
            'compagny' => $compagny,
            'form' => $form,
        ]);
    }

    #[Route('/show', name: 'app_compagny_show', methods: ['GET'])]
    public function show(CompagnyRepository $compagnyRepository): Response
    {
        return $this->render('compagny/show.html.twig', [
            'compagny' => $compagnyRepository->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/edit', name: 'app_compagny_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,  EntityManagerInterface $entityManager, CompagnyRepository $compagnyRepository): Response
    {
        $compagny = $compagnyRepository->findOneBy(['user' => $this->getUser()]);

        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compagny_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('compagny/edit.html.twig', [
            'compagny' => $compagny,
            'form' => $form,
        ]);
    }



    // #[Route('/{id}', name: 'app_compagny_delete', methods: ['POST'])]
    // public function delete(Request $request, Compagny $compagny, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $compagny->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($compagny);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_compagny_index', [], Response::HTTP_SEE_OTHER);
    // }
}
