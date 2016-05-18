<?php

namespace IssuesBundle\Workflow\Action;

use Doctrine\ORM\EntityManager;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Resolution;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;

class SetResolutionAction implements ActionInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute($context)
    {
        $resolution = $context->getData()->get('resolution');

        if ($resolution instanceof Resolution && $context->getEntity() instanceof Issue) {
            $issue = $context->getEntity();

            $issue->setResolution($resolution);

            $this->entityManager->persist($issue);
            $this->entityManager->flush();
        }
    }

    public function initialize(array $options)
    {
    }

    public function setCondition(ExpressionInterface $condition)
    {
    }
}
