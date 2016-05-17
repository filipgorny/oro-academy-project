<?php

namespace IssuesBundle\Tests\Unit\Model\Service;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueCodeGenerator;

class IssueCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratesCode()
    {
        $entityManagerChainMethods = ['getRepository'];
        $queryBuilderMethods = [
            'select', 'where', 'setParameter', 'orderBy', 'setMaxResults'
        ];

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $query = $this->getMock(
            '\StdClass',
            ['getSingleResult']
        );

        foreach ($entityManagerChainMethods as $method) {
            $entityManager->expects($this->any())
                ->method($method)
                ->will($this->returnValue($entityManager));
        }

        foreach ($queryBuilderMethods as $method) {
            $queryBuilder->expects($this->any())
                ->method($method)
                ->will($this->returnValue($queryBuilder));
        }

        $queryBuilder->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($query));

        $queryBuilder->expects($this->any())
            ->method('getSingleResult')
            ->will($this->returnValue(['c' => 2]));

        $entityManager->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder));

        $issueUpdateStamp = new IssueCodeGenerator();

        $issue = new Issue();

        $issueUpdateStamp->populateCode($entityManager, $issue);

        $this->assertNotEmpty($issue->getCode());
    }
}
