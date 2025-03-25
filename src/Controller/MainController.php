<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            
        ]);
    }

    #[Route('/photos', name: 'app_photo')]
    public function photo(): Response
    {
        return $this->render('main/photos.html.twig', [
            
        ]);
    }
}