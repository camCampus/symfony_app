<?php

namespace App\Security;

use App\Entity\Duck;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{

    const DELETE = 'delete';
    const EDIT = 'edit';


    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
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
        $duck = $token->getUser();

        if(!$duck instanceof Duck){
            return false;
        }
        if ($duck != $subject){
            return false;
        }
        return true;
    }

}