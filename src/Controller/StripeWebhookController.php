<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class StripeWebhookController extends AbstractController
{
    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request, InvoiceRepository $invoiceRepository, EntityManagerInterface $entityManager): Response
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $payload = $request->getContent();
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Webhook error: Invalid Payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Webhook error: Invalid Signature', 400);
        }

        // Gérer l'événement checkout.session.completed
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object; // La session de paiement

            // Récupérer l'ID de la facture à partir de la session (assurez-vous d'avoir stocké l'ID de la facture dans la session ou d'avoir un moyen de le retrouver)
            $invoiceId = $session->metadata['invoice_id'];

            // Récupérez et mettez à jour l'entité de la facture
            $invoice = $invoiceRepository->find($invoiceId);
            if ($invoice) {
                $invoice->setStatus("Payé");
                $entityManager->flush();
            }
        }

        return new Response('Webhook handled', 200);
    }
}
