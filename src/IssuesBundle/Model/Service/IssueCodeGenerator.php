<?php

namespace IssuesBundle\Model\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use IssuesBundle\Entity\Issue;

class IssueCodeGenerator
{
    public function populateCode(EntityManager $entityManager, Issue $issue)
    {
        $today = new \DateTime('now');
        $today->setTime(0, 0, 0);

        $r = $entityManager->getRepository('IssuesBundle\Entity\Issue')
            ->createQueryBuilder('i')
            ->select('count(i.id) as c')
            ->orderBy('i.id', 'desc')
            ->setMaxResults(1)
            ->where('i.createdAt >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleResult();

        $id = isset($r['c']) ? (int)$r['c'] + 1: 1;

        $issue->setCode($today->format('Y/m/d').'/'.$id);

        // TODO consider that issues may be deleted
    }
}
