<?php

namespace IssuesBundle\EventListeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueCodeGenerator;
use IssuesBundle\Model\Service\IssueUpdateStamp;

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
    
    public function __construct(IssueUpdateStamp $issueUpdateStamp, IssueCodeGenerator $issueCodeGenerator)
    {
        $this->issueUpdateStamp = $issueUpdateStamp;
        $this->issueCodeGenerator = $issueCodeGenerator;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Issue) {
            $this->issueUpdateStamp->populateCreationAndUpdateStamps($entity);

            $code = $entity->getCode();

            if (empty($code)) {
                $this->issueCodeGenerator->populateCode($args->getEntityManager(), $entity);
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        return $this->prePersist($args);
    }
}
