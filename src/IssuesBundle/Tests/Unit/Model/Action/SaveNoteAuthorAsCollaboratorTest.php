<?php

namespace IssuesBundle\Tests\Unit\Model\Action;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Action\SaveNoteAuthorAsCollaborator;
use IssuesBundle\Model\Service\Collaboration;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SaveNoteAuthorAsCollaboratorTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdatesStamp()
    {
        /**
         * @var ContextAccessor $contextAccessor
         */
        $contextAccessor = $this->getMockBuilder(
            'Oro\Bundle\WorkflowBundle\Model\ContextAccessor'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var IssueUpdateStamp $issueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder(
            'IssuesBundle\Model\Service\IssueUpdateStamp'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $issueUpdateStamp->expects($this->atLeastOnce())
            ->method('populateCreationAndUpdateStamps');

        /**
         * @var Collaboration $collaboration
         */
        $collaboration = $this->getMockBuilder(
            'IssuesBundle\Model\Service\Collaboration'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var TokenStorageInterface $tokenStorage
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $saveNoteAuthorAsCollaborator = new SaveNoteAuthorAsCollaborator(
            $contextAccessor,
            $issueUpdateStamp,
            $collaboration,
            $tokenStorage
        );

        /**
         * @var Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->getMockBuilder(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface'
        )
            ->getMock();

        $saveNoteAuthorAsCollaborator->setDispatcher($eventDispatcher);

        $issue = new Issue();

        $note = $this->getMockBuilder('\StdClass')
            ->setMethods(['getTarget'])
            ->getMock();

        $note->expects($this->atLeastOnce())
            ->method('getTarget')
            ->will($this->returnValue($issue));

        $values = [
            'data' => $note
        ];

        $context = $this->getMockBuilder('\StdClass')
            ->setMethods(['getValues'])
            ->getMock();

        $context->expects($this->atLeastOnce())
            ->method('getValues')
            ->will($this->returnValue($values));

        $saveNoteAuthorAsCollaborator->execute($context);
    }

    public function testUpdatesCollaborators()
    {
        /**
         * @var ContextAccessor $contextAccessor
         */
        $contextAccessor = $this->getMockBuilder(
            'Oro\Bundle\WorkflowBundle\Model\ContextAccessor'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var IssueUpdateStamp $issueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder(
            'IssuesBundle\Model\Service\IssueUpdateStamp'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var Collaboration $collaboration
         */
        $collaboration = $this->getMockBuilder(
            'IssuesBundle\Model\Service\Collaboration'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $collaboration->expects($this->atLeastOnce())
            ->method('updateCollaborators');

        /**
         * @var TokenStorageInterface $tokenStorage
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockBuilder('\StdClass')
            ->setMethods(['getUser'])
            ->getMock();

        $tokenStorage->expects($this->atLeastOnce())
            ->method('getToken')
            ->will($this->returnValue($token));

        $user = $this->getMockBuilder('\StdClass')
            ->getMock();

        $token->expects($this->atLeastOnce())
            ->method('getUser')
            ->will($this->returnValue($user));

        $saveNoteAuthorAsCollaborator = new SaveNoteAuthorAsCollaborator(
            $contextAccessor,
            $issueUpdateStamp,
            $collaboration,
            $tokenStorage
        );

        /**
         * @var Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->getMockBuilder(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface'
        )
            ->getMock();

        $saveNoteAuthorAsCollaborator->setDispatcher($eventDispatcher);

        $issue = new Issue();

        $note = $this->getMockBuilder('\StdClass')
            ->setMethods(['getTarget'])
            ->getMock();

        $note->expects($this->atLeastOnce())
            ->method('getTarget')
            ->will($this->returnValue($issue));

        $values = [
            'data' => $note
        ];

        $context = $this->getMockBuilder('\StdClass')
            ->setMethods(['getValues'])
            ->getMock();

        $context->expects($this->atLeastOnce())
            ->method('getValues')
            ->will($this->returnValue($values));

        $saveNoteAuthorAsCollaborator->execute($context);
    }
}
