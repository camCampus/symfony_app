<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\Duck;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{

    const IS_AUTHOR = 'is_author';
    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::IS_AUTHOR])) {
            return false;
        }

        if (!$subject instanceof Comment) {
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
        if ($duck != $subject->getDuck()){
            if ($duck != $subject->getQuackId()->getDuck())
            return false;
        }
        return true;
    }


}