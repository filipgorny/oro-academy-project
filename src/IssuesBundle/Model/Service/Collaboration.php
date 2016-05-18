<?php

namespace IssuesBundle\Model\Service;

use Doctrine\ORM\EntityManager;
use IssuesBundle\Entity\Issue;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * Manager domain service, for adding users to collaboration list in the Issue model.
 *
 * Class Collaboration
 * @package IssuesBundle\Model
 */
class Collaboration
{
    public function markUserAsCollaborator(User $user, Issue $issue)
    {
        if (!$issue->getCollaborators()->contains($user)) {
            $issue->getCollaborators()->add($user);
        }
    }
}
