<?php

namespace App\Security;

use App\Entity\Duck;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DuckListVoter extends Voter
{
    public function __construct(
        private Security $security
    )
    {
    }

    const ROLE = 'ROLE_ADMIN';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::ROLE])) {
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
        if (in_array($subject->getRoles(), [self::ROLE])) {
            return true;
        }
        return false;
    }
}