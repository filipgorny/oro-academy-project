<?php

namespace IssuesBundle\EventListeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\Collaboration;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Oro\Bundle\NoteBundle\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class IssueNotePersistingListener
 * @package IssuesBundle\EventListeners
 */
class IssueNotePersistingListener
{
    /**
     * @var IssueUpdateStamp
     */
    private $issueUpdateStamp;

    /**
     * @var Collaboration
     */
    private $collaboration;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * IssueNotePersistingListener constructor.
     * @param IssueUpdateStamp $issueUpdateStamp
     * @param Collaboration $collaboration
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        IssueUpdateStamp $issueUpdateStamp,
        Collaboration $collaboration,
        TokenStorageInterface $tokenStorage
    ) {
        $this->issueUpdateStamp = $issueUpdateStamp;
        $this->collaboration = $collaboration;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Note) {
            $target = $entity->getTarget();

            if ($target instanceof Issue) {
                $this->issueUpdateStamp->populateCreationAndUpdateStamps($target);

                if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
                    $this->collaboration->updateCollaborators($target, [$this->tokenStorage->getToken()->getUser()]);
                }
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        return $this->prePersist($args);
    }
}
