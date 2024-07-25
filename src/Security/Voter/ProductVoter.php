<?php

namespace App\Security\Voter;

use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['PRODUCT_VIEW', 'PRODUCT_EDIT', 'PRODUCT_DELETE'])
            && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, $product, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'PRODUCT_VIEW':
            case 'PRODUCT_EDIT':
            case 'PRODUCT_DELETE':
                return $product->getCompanyReference() === $user->getCompany();
        }

        return false;
    }
}
