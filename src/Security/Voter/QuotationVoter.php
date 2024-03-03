<?php

namespace App\Security\Voter;

use App\Entity\Quotation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuotationVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // ici définir les permissions que ce voter va vérifier
        return in_array($attribute, ['QUOTATION_VIEW', 'QUOTATION_EDIT', 'QUOTATION_DELETE'])
            && $subject instanceof Quotation;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $quotation = $subject;

        // Vérifier que le devis appartient à l'entreprise de l'utilisateur
        switch ($attribute) {
            case 'QUOTATION_VIEW':
            case 'QUOTATION_EDIT':
            case 'QUOTATION_DELETE':
                return $quotation->getUserReference()->getCompany() === $user->getCompany();
        }

        return false;
    }
}
