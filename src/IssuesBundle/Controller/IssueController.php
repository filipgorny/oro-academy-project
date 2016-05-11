<?php

namespace IssuesBundle\Controller;

use IssuesBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/view/{id}", name="orocrm_issue_view", requirements={"id"="\d+"})
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }
}
