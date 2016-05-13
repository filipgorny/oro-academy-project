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
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function markUserAsCollaborator(User $user, Issue $issue)
    {
        // do not add the reporter or the assignee
        if (
            ($issue->getReporter() !== null)
            &&
            (
                ($user->getId() == $issue->getReporter()->getId())
                ||
                (($issue->getAssignee() !== null) && ($issue->getAssignee()->getId() == $user->getId()))
            )
        ) {
            return;
        }

        if (!$issue->getCollaborators()->contains($user)) {
            $issue->getCollaborators()->add($user);

            $this->entityManager->persist($issue);
            $this->entityManager->flush();
        }
    }
}
