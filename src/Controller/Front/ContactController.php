<?php
namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
            $email = (new TemplatedEmail())
                ->from(new Address($formData['email']))
                ->to('destination@example.com')
                ->subject($formData['subject'])
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'message' => $formData['message'] 
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé.');
            
            return $this->redirectToRoute('front_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
