<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }
    #[Route('/front', name: 'app_front')]
    public function indexx(): Response
    {
        return $this->render('Front.html.twig');
    }
}