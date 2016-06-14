<?php

namespace IssuesBundle\Test\Model\Service\Provider;

use Doctrine\ORM\EntityManager;
use IssuesBundle\Model\Service\Provider\StatusesProvider;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

class StatusesProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return EntityManager
     */
    private function mockEntityManager()
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
            ['getScalarResult']
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

        $query->expects($this->any())
            ->method('getScalarResult')
            ->will(
                $this->returnValue([['s_id' => 2, 's_label' => 'label 0000'], ['s_id' => 3, 's_label' => 'label 2']])
            );

        $entityManager->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder));

        return $entityManager;
    }

    public function testReturnsStatusesArray()
    {
        $statusesProvider = new StatusesProvider($this->mockEntityManager());

        $this->assertNotEmpty($statusesProvider->getStatusesArray());
    }
}
