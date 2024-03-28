<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Repository\ContenuPanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contenu/panier')]
class ContenuPanierController extends AbstractController
{
    #[Route('/', name: 'app_contenu_panier_index', methods: ['GET'])]
    public function index(ContenuPanierRepository $contenuPanierRepository): Response
    {
        return $this->render('contenu_panier/index.html.twig', [
            'contenu_paniers' => $contenuPanierRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_contenu_panier_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id, LoggerInterface $l): Response
    {
        $l->error('1');
        // Récupérer le produit associé à l'identifiant
        $produit = $entityManager->getRepository(Produit::class)->findOneById($id);
    
        // Créer une nouvelle instance de ContenuPanier
        $contenuPanier = new ContenuPanier();
        // Assurez-vous d'associer le produit récupéré à l'entité ContenuPanier
        $contenuPanier->setProduit($produit);
        $contenuPanier->setDate(new \DateTime());
    
        // Créer le formulaire en passant l'entité ContenuPanier
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form->handleRequest($request);
        $l->error('2');
        // if ($form->isSubmitted() && $form->isValid()) {
            $l->error('3');
            // Ajouter le produit au panier
            $contenuPanier->setQuantite($contenuPanier->getQuantite() + 1);
            $entityManager->persist($contenuPanier);
            $entityManager->flush();
    
            // Rediriger l'utilisateur vers la page du contenu du panier
            return $this->redirectToRoute('app_contenu_panier_index');
        // }
    
        // return $this->render('contenu_panier/new.html.twig', [
        //     'contenu_panier' => $contenuPanier,
        //     'form' => $form->createView(),
        // ]);
    }


    #[Route('/{id}', name: 'app_contenu_panier_show', methods: ['GET'])]
    public function show(ContenuPanier $contenuPanier): Response
    {
        return $this->render('contenu_panier/show.html.twig', [
            'contenu_panier' => $contenuPanier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contenu_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContenuPanier $contenuPanier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contenu_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contenu_panier/edit.html.twig', [
            'contenu_panier' => $contenuPanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contenu_panier_delete', methods: ['POST'])]
    public function delete(Request $request, ContenuPanier $contenuPanier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contenuPanier->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($contenuPanier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contenu_panier_index', [], Response::HTTP_SEE_OTHER);
    }

}
