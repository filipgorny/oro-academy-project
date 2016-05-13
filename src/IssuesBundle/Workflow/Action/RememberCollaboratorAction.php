<?php

namespace IssuesBundle\Workflow\Action;

use IssuesBundle\Model\Service\Collaboration;
use Oro\Bundle\WorkflowBundle\Exception\InvalidParameterException;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * RememberCollaborantAction constructor.
     * @param Collaboration $collaboration
     */
    public function __construct(Collaboration $collaboration, TokenStorageInterface $tokenStorage)
    {
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
            $this->collaboration->markUserAsCollaborator($this->tokenStorage->getToken()->getUser(), $context->getEntity());
        }
    }

    public function initialize(array $options)
    {
    }

    public function setCondition(ExpressionInterface $condition)
    {
    }
}
