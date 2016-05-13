<?php

namespace IssuesBundle\Tests\Unit\Model\Service;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\Collaboration;
use Oro\Bundle\UserBundle\Entity\User;

class CollaborationTest extends \PHPUnit_Framework_TestCase
{
    public function testAddingUserAsCollaborator()
    {
        $issue = new Issue();

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $collaboration = new Collaboration($entityManager);

        $user1 = new User();
        $user1->setUsername('test collaborator 1');

        $user2 = new User();
        $user2->setUsername('test collaborator 2');

        $collaboration->markUserAsCollaborator($user1, $issue);
        // adding second time to test if it doesn't double
        $collaboration->markUserAsCollaborator($user1, $issue);

        $collaboration->markUserAsCollaborator($user2, $issue);

        $this->assertEquals(2, $issue->getCollaborators()->count());
    }

    public function testDoesntAddReporterAsAssignee()
    {
        $user1 = new User();
        $user1->setUsername('test collaborator 1');

        $issue = new Issue();
        $issue->setReporter($user1);

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $collaboration = new Collaboration($entityManager);
        $collaboration->markUserAsCollaborator($user1, $issue);

        $this->assertEquals(0, $issue->getCollaborators()->count());
    }
}
