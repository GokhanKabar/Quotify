<?php

namespace App\Controller\Company;

use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'payment_index')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->security->getUser();

        $companyName = $user->getCompany()->getCompanyName();

        $companyId = $user->getCompany()->getId();
        $invoices = $invoiceRepository->findInvoicesByCompany($companyId);

        return $this->render('company/payment/index.html.twig', ['companyName' => $companyName, 'invoices' => $invoices,]);
    }

    #[Route('/accountant', name: 'payment_accountant')]
    public function accountant(InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->security->getUser();

        $companyName = $user->getCompany()->getCompanyName();

        $companyId = $user->getCompany()->getId();
        $invoices = $invoiceRepository->findInvoicesByCompanyAccountant($companyId);

        return $this->render('company/payment/accountant.html.twig', ['companyName' => $companyName, 'invoices' => $invoices,]);
    }

}
