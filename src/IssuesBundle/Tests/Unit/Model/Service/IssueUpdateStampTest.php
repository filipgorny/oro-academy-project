<?php

namespace IssuesBundle\Tests\Unit\Model\Service;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueUpdateStampTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdatesDates()
    {
        $user = new User();
        $user->setUsername('test user');

        $token = $this->getMock('\Symfony\Component\Security\Core\Authentication\Token', ['getUser']);
        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $tokenManager = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $tokenManager->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $issueUpdateStamp = new IssueUpdateStamp($tokenManager);

        $issue = new Issue();

        $previousUpdatedAtDate = new \DateTime('now');
        $previousUpdatedAtDate->modify('-5 days');

        $issue->setUpdatedAt($previousUpdatedAtDate);

        $issueUpdateStamp->populateCreationAndUpdateStamps($issue);

        $this->assertInstanceOf('\DateTime', $issue->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $issue->getUpdatedAt());

        $this->assertNotEquals($previousUpdatedAtDate, $issue->getUpdatedAt());

        $this->assertEquals($user, $issue->getUpdatedBy());
    }
}
