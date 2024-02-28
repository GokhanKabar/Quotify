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
    #[Route('/webhook/stripe', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Votre clé secrète de webhook de l'environnement .env
        // $endpointSecret = $this->getParameter('stripe.webhook_secret');

        // $payload = $request->getContent();
        // $sigHeader = $request->headers->get('Stripe-Signature');

        // try {
        //     $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        // } catch (\UnexpectedValueException $e) {
        //     // Contenu invalide
        //     return new Response('Invalid payload', 400);
        // } catch (\Stripe\Exception\SignatureVerificationException $e) {
        //     // Signature invalide
        //     return new Response('Invalid signature', 400);
        // }

        // // Gérez l'événement
        // if ($event->type === 'checkout.session.completed') {
        //     $session = $event->data->object;

        $payload = $request->getContent();
        dd($payload);

        // Simplement créer l'événement sans vérifier la signature
        try {
            $event = json_decode($payload, true);
            if (null === $event) {
                throw new \Exception('Failed to decode JSON');
            }
        } catch (\Exception $e) {
            return new Response('Invalid payload: ' . $e->getMessage(), 400);
        }

        // Vous pouvez maintenant utiliser $event comme un tableau PHP pour accéder aux données
        // Assurez-vous de vérifier le type d'événement et de traiter l'événement comme vous le souhaitez
        if (isset($event['type']) && $event['type'] === 'checkout.session.completed') {
            // Exemple de traitement de l'événement checkout.session.completed
             $session = $event['data']['object'];
            // Récupérez l'ID de la session ou une référence à la commande
            $invoiceId = $session->metadata->invoice_id;
            $invoice = $entityManager->getRepository(Invoice::class)->find($invoiceId);

            if (!$invoice) {
                return new Response(sprintf('Invoice ID %s not found', $invoiceId), 404);
            }
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

        return new Response('Webhook handled', 200);
    }
}
