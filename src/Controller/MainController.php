<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MenusRepository;

final class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            
        ]);
    }

    #[Route('/menus', name: 'app_menus')]
    public function menus(MenusRepository $menusRepository): Response
    {
        $

        return $this->render('main/menus.html.twig', [
            
        ]);
    }
}
        $tweets = $tweetsRepository->findBy([], ['createdAt' => 'DESC']);



        return $this->render('tweets/index.html.twig', [
            'allTweets' => $allTweets,
           
        ]);
    }