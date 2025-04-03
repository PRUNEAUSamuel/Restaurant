<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(MenusRepository $menusRepository): Response
    {
        $menu = $menusRepository->findOneBy([
            'actualMenu' => true
        ]);

        if (!$menu) {
            throw $this->createNotFoundException('Menu not found');
        }

        $produit = $menu->getProduits();
        $produits = $produit->toArray();

        $entrees = array_filter($produits, function($produit) {
            return $produit->getCategorie() === 'entree';
        });
        $plats = array_filter($produits, function($produit) {
            return $produit->getCategorie() === 'plat';
        });
        $desserts = array_filter($produits, function($produit) {
            return $produit->getCategorie() === 'dessert';
        });
        $boissons = array_filter($produits, function($produit) {
            return $produit->getCategorie() === 'boisson';
        });

        return $this->render('main/menus.html.twig', [
            'menu' => $menu,
            'entrees' => $entrees,
            'plats' => $plats,
            'desserts' => $desserts,
            'boissons' => $boissons,
        ]);
    }
}
