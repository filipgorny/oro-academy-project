<?php

namespace IssuesBundle\Model\Action;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\Collaboration;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Oro\Bundle\WorkflowBundle\Model\Action\AbstractAction;
use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SaveNoteAuthorAsCollaborator extends AbstractAction
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
     * @param ContextAccessor $contextAccessor
     */
    public function __construct(
        ContextAccessor $contextAccessor,
        IssueUpdateStamp $issueUpdateStamp,
        Collaboration $collaboration,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($contextAccessor);

        $this->issueUpdateStamp = $issueUpdateStamp;
        $this->collaboration = $collaboration;
        $this->tokenStorage = $tokenStorage;
    }

    protected function executeAction($context)
    {
        $values = $context->getValues();
        $note = $values['data'];
        $target = $note->getTarget();

        if ($target instanceof Issue) {
            $this->updateUpdateStamp($target);
            $this->updateCollaborators($target);
        }
    }

    public function initialize(array $options)
    {
        //
    }

    private function updateUpdateStamp(Issue $issue)
    {
        $this->issueUpdateStamp->populateCreationAndUpdateStamps($issue);
    }

    private function updateCollaborators(Issue $issue)
    {
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
            $this->collaboration->updateCollaborators($issue, [$this->tokenStorage->getToken()->getUser()]);
        }
    }
}
