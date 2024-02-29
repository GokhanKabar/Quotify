<?php

namespace App\Security\Voter;

use App\Entity\Invoice;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class InvoiceVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['INVOICE_VIEW', 'INVOICE_EDIT', 'INVOICE_DELETE'])
            && $subject instanceof Invoice;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $invoice = $subject;

        // Vérifier que la facture appartient à l'entreprise de l'utilisateur
        switch ($attribute) {
            case 'INVOICE_VIEW':
            case 'INVOICE_EDIT':
            case 'INVOICE_DELETE':
                return $invoice->getUserReference()->getCompany() === $user->getCompany();
        }

        return false;
    }
}
