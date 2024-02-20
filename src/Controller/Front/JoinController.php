<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoinController extends AbstractController
{
    #[Route('/join', name: 'join')]
    public function index(): Response
    {
        return $this->render('join/index.html.twig', [
            'controller_join' => 'JoinController',
        ]);
    }
}