<?php

namespace IssuesBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use IssuesBundle\Entity\Issue;

/**
 * Class PriorityRepository
 * @package IssuesBundle\Entity\Repository
 */
class PriorityRepository extends EntityRepository
{
    /**
     * @return Issue[]
     */
    public function findAllAlphabetically()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Issue[]
     */
    public function findAllAlphabeticallyArray()
    {
        $priorities = $this->createQueryBuilder('p')
            ->orderBy('p.name')
            ->getQuery()
            ->getScalarResult();

        $result = [];

        foreach ($priorities as $priority) {
            $result[$priority['p_id']] = $priority['p_name'];
        }

        return $result;
    }
}
