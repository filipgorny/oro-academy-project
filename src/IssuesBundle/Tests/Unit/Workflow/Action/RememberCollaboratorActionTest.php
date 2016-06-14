<?php

namespace IssuesBundle\Workflow\Action;

use IssuesBundle\Entity\Issue;

class RememberCollaboratorActionTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdatesCollaborators()
    {
        $collaboration = $this->getMockBuilder('IssuesBundle\Model\Service\Collaboration')
            ->disableOriginalConstructor()
            ->getMock();

        $collaboration->expects($this->atLeastOnce())
            ->method('updateCollaborators');

        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage->expects($this->atLeastOnce())
            ->method('getToken')
            ->will($this->returnValue($token));

        $rememberCollaboratorAction = new RememberCollaboratorAction($collaboration, $tokenStorage);

        $context = $this->getMockBuilder('\StdClass')
            ->setMethods(['getEntity'])
            ->getMock();

        $issue = new Issue();

        $context->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $rememberCollaboratorAction->execute($context);
    }
}
