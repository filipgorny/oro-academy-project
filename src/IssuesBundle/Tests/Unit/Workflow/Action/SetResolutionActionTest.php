<?php

namespace IssuesBundle\Workflow\Action;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Resolution;

class SetResolutionActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSetsResolution()
    {
        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $rememberCollaboratorAction = new SetResolutionAction($entityManager);

        $context = $this->getMockBuilder('\StdClass')
            ->setMethods(['getEntity', 'getData'])
            ->getMock();

        $issue = new Issue();

        $data = $this->getMockBuilder('\StdClass')
            ->setMethods(['get'])
            ->getMock();

        $resolution = new Resolution();

        $data->expects($this->any())
            ->method('get')
            ->will($this->returnValue($resolution));

        $context->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $context->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($data));

        $rememberCollaboratorAction->execute($context);

        $this->assertEquals($issue->getResolution(), $resolution);
    }
}
