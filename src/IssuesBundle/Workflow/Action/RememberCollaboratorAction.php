<?php

namespace IssuesBundle\Workflow\Action;

use IssuesBundle\Model\Service\Collaboration;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RememberCollaboratorAction
 * @package IssuesBundle\Workflow\Action
 */
class RememberCollaboratorAction implements ActionInterface
{
    /**
     * @var Collaboration
     */
    private $collaboration;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * RememberCollaboratorAction constructor.
     * @param Collaboration $collaboration
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        Collaboration $collaboration,
        TokenStorageInterface $tokenStorage
    ) {
        $this->collaboration = $collaboration;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Execute action.
     *
     * @param mixed $context
     */
    public function execute($context)
    {
        if ($this->tokenStorage->getToken()) {
            $this->collaboration->markUserAsCollaborator(
                $this->tokenStorage->getToken()->getUser(),
                $context->getEntity()
            );
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
