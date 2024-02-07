<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class CompanyController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/company', name: 'app_company')]
    public function users_list(): Response
    {
        $user = $this->security->getUser();

        if (!$user || !$user->getCompany()) {
            return $this->redirectToRoute('app_login');
        }

        $company = $user->getCompany();
        $companyId = $company->getId();

        $users = $this->entityManager->getRepository(User::class)->findBy(['company' => $companyId]);

        return $this->render('company/users_list.html.twig', [
            'controller_name' => 'CompanyController',
            'company' => $company,
            'users' => $users,
        ]);
    }
}
