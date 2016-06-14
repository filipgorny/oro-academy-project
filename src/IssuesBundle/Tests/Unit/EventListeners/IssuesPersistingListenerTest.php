<?php

namespace IssuesBundle\Tests\Unit\EventListeners;

use IssuesBundle\Entity\Issue;
use IssuesBundle\EventListeners\IssuesPersistingListener;
use IssuesBundle\Model\Service\Collaboration;
use IssuesBundle\Model\Service\IssueCodeGenerator;
use IssuesBundle\Model\Service\IssueUpdateStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssuesPersistingListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlesIssueWhenPersist()
    {
        /**
         * @var IssueUpdateStamp $issueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder('IssuesBundle\Model\Service\IssueUpdateStamp')
            ->disableOriginalConstructor()
            ->getMock();

        $issueUpdateStamp->expects($this->atLeastOnce())
            ->method('populateCreationAndUpdateStamps');

        /**
         * @var IssueCodeGenerator $issueCodeGenerator
         */
        $issueCodeGenerator = $this->getMockBuilder('IssuesBundle\Model\Service\IssueCodeGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $issueCodeGenerator->expects($this->atLeastOnce())
            ->method('populateCode');

        /**
         * @var Collaboration $collaboration
         */
        $collaboration = $this->getMockBuilder('IssuesBundle\Model\Service\Collaboration')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var TokenStorageInterface
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $listener = new IssuesPersistingListener(
            $issueUpdateStamp,
            $issueCodeGenerator,
            $collaboration,
            $tokenStorage
        );

        $lifeCycleArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $issue = new Issue();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $listener->prePersist($lifeCycleArgs);
    }

    public function testHandlesIssueWhenUpdate()
    {
        /**
         * @var IssueUpdateStamp $issueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder('IssuesBundle\Model\Service\IssueUpdateStamp')
            ->disableOriginalConstructor()
            ->getMock();

        $issueUpdateStamp->expects($this->atLeastOnce())
            ->method('populateCreationAndUpdateStamps');

        /**
         * @var IssueCodeGenerator $issueCodeGenerator
         */
        $issueCodeGenerator = $this->getMockBuilder('IssuesBundle\Model\Service\IssueCodeGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $issueCodeGenerator->expects($this->atLeastOnce())
            ->method('populateCode');

        /**
         * @var Collaboration $collaboration
         */
        $collaboration = $this->getMockBuilder('IssuesBundle\Model\Service\Collaboration')
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var TokenStorageInterface
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $listener = new IssuesPersistingListener(
            $issueUpdateStamp,
            $issueCodeGenerator,
            $collaboration,
            $tokenStorage
        );

        $lifeCycleArgs = $this->getMockBuilder('Doctrine\ORM\Event\PreUpdateEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $issue = new Issue();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $classMetaData = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetaData')
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->expects($this->atLeastOnce())
            ->method('getClassMetadata')
            ->will($this->returnValue($classMetaData));

        $uow = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->expects($this->atLeastOnce())
            ->method('getUnitOfWork')
            ->will($this->returnValue($uow));

        $listener->preUpdate($lifeCycleArgs);
    }
}
