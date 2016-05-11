<?php

namespace IssuesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/issue")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/view/{id}", name="orocrm_issue_view", requirements={"id"="\d+"})
     * @Template
     */
    public function viewAction(Task $task)
    {
        return array('entity' => $task);
    }
}
