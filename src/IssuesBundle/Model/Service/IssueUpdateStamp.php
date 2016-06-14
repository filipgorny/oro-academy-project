<?php

namespace IssuesBundle\Model\Service;

use IssuesBundle\Entity\Issue;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssueUpdateStamp
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    public function populateCreationAndUpdateStamps(Issue $issue)
    {
        if ($issue->getId() < 1) {
            $issue->setCreatedAt(new \DateTime('now'));
        }

        $issue->setUpdatedAt(new \DateTime('now'));
        $issue->setUpdatedBy($this->getCurrentUser());
    }

    /**
     * @return User|null
     */
    private function getCurrentUser()
    {
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ($user instanceof User) {
                return $user;
            }
        }
    }
}
