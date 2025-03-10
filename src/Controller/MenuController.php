<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
    #[Route('/menu/{id}', name: 'app_menu')]
    public function index($id, MenusRepository $menusRepository): Response
    {
        $menu = $menusRepository->find($id);

        if (!$menu) {
            throw $this->createNotFoundException('Menu not found');
        }

        $produits = $menu->getProduits();

        return $this->render('main/menus.html.twig', [
            'menu' => $menu,
            'produits' => $produits,
        ]);
    }
}
