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

        $collaboration->updateCollaborators($issue, [$user1]);
        // adding second time to test if it doesn't double
        $collaboration->updateCollaborators($issue, [$user1]);

        $collaboration->updateCollaborators($issue, [$user2]);

        $this->assertEquals(2, $issue->getCollaborators()->count());
    }
}
