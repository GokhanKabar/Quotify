<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StripeController extends AbstractController
{
    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        // Logique à exécuter après un paiement réussi
        // Par exemple, afficher un message de remerciement ou de confirmation
        return $this->render('stripe/success.html.twig');
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        // Logique à exécuter après une annulation de paiement
        // Par exemple, afficher un message d'erreur ou une option pour réessayer
        return $this->render('stripe/cancel.html.twig');
    }
}
