<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error-custom', name: 'app_error_custom')]
    public function customError(): Response
    {
        // Utilisez le nom du fichier twig que vous souhaitez rendre pour votre page d'erreur
        return $this->render('error/custom_error.html.twig', [
            'message' => 'Accès refusé. Vous n\'êtes pas autorisé à accéder à cette page.',
        ]);
    }
}
