<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CustomerVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['CUSTOMER_VIEW', 'CUSTOMER_EDIT', 'CUSTOMER_DELETE'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $customer = $subject;

        switch ($attribute) {
            case 'CUSTOMER_VIEW':
            case 'CUSTOMER_EDIT':
            case 'CUSTOMER_DELETE':
                return $customer->getCompany() === $user->getCompany();
        }

        return false;
    }
}
