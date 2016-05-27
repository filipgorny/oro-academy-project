<?php

namespace IssuesBundle\EventListeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
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
        $entity = $args->getEntity();

        if ($entity instanceof Issue) {
            $this->issueUpdateStamp->populateCreationAndUpdateStamps($entity);

            $token = $this->tokenStorage->getToken();

            if ($token && $token->getUser()) {
                $this->collaboration->markUserAsCollaborator($token->getUser(), $entity);
            }

            $code = $entity->getCode();

            if (empty($code)) {
                $this->issueCodeGenerator->populateCode($args->getEntityManager(), $entity);
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
