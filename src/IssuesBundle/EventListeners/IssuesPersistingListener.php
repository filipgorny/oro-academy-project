<?php

namespace IssuesBundle\EventListeners;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\Collaboration;
use IssuesBundle\Model\Service\IssueCodeGenerator;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class IssuesPersistingListener
 * @package IssuesBundle\EventListeners
 */
class IssuesPersistingListener
{
    /**
     * @var IssueUpdateStamp
     */
    private $issueUpdateStamp;

    /**
     * @var IssueCodeGenerator
     */
    private $issueCodeGenerator;

    /**
     * @var Collaboration
     */
    private $collaboration;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * IssuesPersistingListener constructor.
     * @param IssueUpdateStamp $issueUpdateStamp
     * @param IssueCodeGenerator $issueCodeGenerator
     * @param Collaboration $collaboration
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        IssueUpdateStamp $issueUpdateStamp,
        IssueCodeGenerator $issueCodeGenerator,
        Collaboration $collaboration,
        TokenStorageInterface $tokenStorage
    ) {
        $this->issueUpdateStamp = $issueUpdateStamp;
        $this->issueCodeGenerator = $issueCodeGenerator;
        $this->collaboration = $collaboration;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Issue) {
            $this->handleIssue($args->getEntity());
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($args->getEntity() instanceof Issue) {
            $entity = $args->getEntity();

            $this->handleIssue($entity);

            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    /**
     * @param $entity
     */
    private function handleIssue($entity)
    {
        $this->issueUpdateStamp->populateCreationAndUpdateStamps($entity);
    }
}
