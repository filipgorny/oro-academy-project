<?php

namespace IssuesBundle\Workflow\Action;

use Doctrine\ORM\EntityManager;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Resolution;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;

/**
 * Class SetResolutionAction
 * @package IssuesBundle\Workflow\Action
 */
class SetResolutionAction implements ActionInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SetResolutionAction constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $context
     */
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

    /**
     * @param array $options
     * @return null
     */
    public function initialize(array $options)
    {
    }

    /**
     * @param ExpressionInterface $condition
     * @return null
     */
    public function setCondition(ExpressionInterface $condition)
    {
    }
}
