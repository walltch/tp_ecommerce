<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Repository\ContenuPanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route('/new/{id}', name: 'app_contenu_panier_new', methods: ['POST'])]
    public function new(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le produit associé à l'identifiant
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        // Vérifier si le produit existe déjà dans le panier
        $contenuPanier = $entityManager->getRepository(ContenuPanier::class)->findOneBy(['produit' => $produit]);

        if ($contenuPanier) {
            // Si le produit existe déjà, augmenter la quantité
            $contenuPanier->setQuantite($contenuPanier->getQuantite() + 1);
        } else {
            // Si le produit n'existe pas, créer un nouvel élément
            $contenuPanier = new ContenuPanier();
            $contenuPanier->setProduit($produit);
            $contenuPanier->setQuantite(1);
            $contenuPanier->setDate(new \DateTime());

            $entityManager->persist($contenuPanier);
        }

        $entityManager->flush();

        // Rediriger l'utilisateur vers la page du contenu du panier
        return $this->redirectToRoute('app_contenu_panier_index');
    }


    #[Route('/{id}', name: 'app_contenu_panier_show', methods: ['GET'])]
    public function show(SessionInterface $session,Security $security,ContenuPanier $contenuPanier): Response
    {
        if ($security->getUser()) {
            // Rediriger vers le panier s'il est connecté
            $session->set('session', $contenuPanier);
            return $this->redirectToRoute('app_panier_new');
        } else {
            $session->set('previous_page', $this->generateUrl('app_contenu_panier_show'));
            // Rediriger vers la page de connexion sinon
            $session->set('session', $contenuPanier);
            return $this->redirectToRoute('app_login');
        }
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
