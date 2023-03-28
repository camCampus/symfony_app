<?php

namespace App\Security;

use App\Entity\Duck;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public function __construct(
        private Security $security
    )
    {
    }

    const USER = 'ROLE_USER';


    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::USER])) {
            return false;
        }
        if (!$subject instanceof Duck) {
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (in_array($subject->getRoles(), [self::USER])) {
            return true;
        }
        return false;
    }
}