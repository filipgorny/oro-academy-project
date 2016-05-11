<?php

namespace IssuesBundle\Controller;

use IssuesBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="issues.issues_index", requirements={"id"="\d+"})
     */
    public function indexAction(Issue $issue)
    {
        return new Response();
    }

    /**
     * @Route("/view/{id}", name="issues.issue_view", requirements={"id"="\d+"})
     * @AclAncestor("issues.issue_view")
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        return array(
            'entity' => $issue,
        );
    }

    /**
     * @Route("/update/{id}", name="issues.issue_update", requirements={"id"="\d+"})
     */
    public function updateAction(Issue $issue)
    {
        return new Response();
    }

    /**
     * @Route("/update/{id}", name="issues.issue_api_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(Issue $issue)
    {
        return new Response();
    }
}
