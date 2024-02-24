<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Quotation;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends AbstractController
{
    // #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    // public function stripeWebhook(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $payload = $request->getContent();
    //     $sigHeader = $request->headers->get('Stripe-Signature');

    //     // Remplacez 'whsec_...' par votre clé secrète de endpoint de webhook
    //     $endpointSecret = 'whsec_...';

    //     try {
    //         $event = Webhook::constructEvent(
    //             $payload,
    //             $sigHeader,
    //             $endpointSecret
    //         );
    //     } catch(\UnexpectedValueException $e) {
    //         // En cas d'erreur de parsing, retournez une réponse d'erreur à Stripe
    //         return new Response('Webhook error: Invalid payload', 400);
    //     } catch(SignatureVerificationException $e) {
    //         // En cas d'échec de vérification de la signature
    //         return new Response('Webhook error: Invalid signature', 400);
    //     }

    //     // Gérez les événements de paiement réussi
    //     if ($event->type === 'checkout.session.completed') {
    //         $session = $event->data->object;

    //         // Récupérez l'ID de la session ou une référence au devis
    //         $quotationId = $session->metadata->quotation_id;

    //         $quotation = $entityManager->getRepository(Quotation::class)->find($quotationId);
    //         if ($quotation) {
    //             $quotation->setPaymentStatus('Payé');
    //             $entityManager->flush();
    //         }
    //     }

    //     return new Response('Webhook handled', 200);
    // }
}
