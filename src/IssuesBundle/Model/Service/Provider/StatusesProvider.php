<?php

namespace IssuesBundle\Model\Service\Provider;

use Doctrine\ORM\EntityManager;

/**
 * Class StatusesProvider
 * @package IssuesBundle\Model\Service\Provider
 */
class StatusesProvider
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * StatusesProvider constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getStatusesArray()
    {
        $result = [];

        $steps = $this->entityManager->getRepository('Oro\Bundle\WorkflowBundle\Entity\WorkflowStep')
            ->createQueryBuilder('s')
            ->orderBy('s.label', 'asc')
            ->getQuery()
            ->getScalarResult();

        foreach ($steps as $step) {
            $result[$step['s_id']] = $step['s_label'];
        }

        return $result;
    }
}
