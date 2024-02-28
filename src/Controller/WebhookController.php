<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Stripe\Stripe;
use Stripe\Webhook;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Invoice;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\Mime\Address;

class WebhookController extends AbstractController
{
    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Votre clé secrète de webhook de l'environnement .env
        $endpointSecret = $this->getParameter('stripe.webhook_secret');

        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            // Contenu invalide
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Signature invalide
            return new Response('Invalid signature', 400);
        }

        // Gérez l'événement
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Récupérez l'ID de la session ou une référence à la commande
            $invoiceId = $session->metadata->invoice_id;
            $invoice = $entityManager->getRepository(Invoice::class)->find($invoiceId);

            if ($invoice) {
                // Mettez à jour le statut de la commande
                $invoice->setPaymentStatus('Payé');
                $entityManager->flush();

                $companyEmail = $invoice->getUserReference()->getCompany()->getEmail();

                // Envoyez un e-mail à l'administrateur
                $email = (new Email())
                    ->from(new Address('no-reply@quotify.fr', 'Quotify'))
                    ->to($companyEmail)
                    ->subject('Confirmation de paiement reçue')
                    ->text("Le paiement pour la commande n°$invoiceId a été effectué avec succès.");

                $mailer->send($email);
            }
        }

        return new Response('Webhook handled', 200);
    }
}
