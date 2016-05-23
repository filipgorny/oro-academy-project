<?php

namespace IssuesBundle\Controller;

use IssuesBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="issues.issues_index")
     * @Template
     */
    public function indexAction()
    {
        return [
            'entity_class' => 'IssueBundle\Entity\Issue'
        ];
    }

    /**
     * @Route("/view/{id}", name="issues.issue_view", requirements={"id"="\d+"})
     * @AclAncestor("issues.issue_view")
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        if ($issue->isDeleted()) {
            throw new NotFoundHttpException();
        }

        return array(
            'entity' => $issue,
        );
    }

//* @Acl(
//*     id="issues.issue_create",
//*     type="entity",
//*     class="IssuesBundle:Issue",
//*     permission="CREATE"
//* )
    /**
     * @Route("/create/{parentId}", name="issues.issue_create", defaults={"parentId" = 0})
     * @Template("IssuesBundle:Issue:update.html.twig")
     */
    public function createAction($parentId, Request $request)
    {
        $issue = new Issue();

        if ($parentId > 0) {
            $parentIssue = $this->getDoctrine()->getRepository('IssuesBundle:Issue')->find($parentId);

            if ($parentIssue === null) {
                throw new NotFoundHttpException('Parent issue not found.');
            }

            $issue->setParent($parentIssue);
            $issue->setType(Issue::TYPE_SUBTASK);
        }

        return $this->updateAction($issue, $request);
    }

    // TODO solve ACL issue
    // - when I add this entry, I am not able to edit an issue even if I'm admin
    //
    // * @Acl(
    // *     id="issues.issue_update",
    // *     type="entity",
    // *     class="IssuesBundle:Issue",
    // *     permission="UPDATE"
    // * )
    /**
     * @Route("/update/{id}", name="issues.issue_update", requirements={"id"="\d+"})
     * @Template("IssuesBundle:Issue:update.html.twig")
     */
    public function updateAction(Issue $issue, Request $request)
    {
        if ($issue->isDeleted()) {
            throw new NotFoundHttpException();
        }
 
        $form = $this->get('form.factory')->create('issue_type', $issue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $successMessage = $issue->getId() == 0 ?
                'issues.issue.flashMessages.create.success' :
                'issues.issue.flashMessages.update.success';

            /**
             * @var $issue Issue
             */
            $issue = $form->getData();

            $currentUser = $this->get('security.context')->getToken()->getUser();

            if ($issue->getAssignee() === null) {
                $issue->setAssignee($currentUser);
            }

            $issue->setOwner($currentUser);
            $issue->setReporter($currentUser);

            $em = $this->getDoctrine()->getManager();

            $em->persist($issue);
            $em->flush();

            if ($issue->getId()) {
                $this->addFlash(
                    'success',
                    $this->get('translator')
                        ->trans($successMessage)
                );
            }

            return $this->redirectToRoute(
                'issues.issue_view',
                ['id' => $issue->getId()]
            );
        }

        return [
            'entity' => $issue,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/delete/{id}", name="issues.issue_delete",
     *     requirements={"id"="\d+"})
     */
    public function deleteAction(Issue $issue)
    {
        $em = $this->getDoctrine()->getManager();

        $issue->setDeleted(true);

        $em->persist($issue);
        $em->flush();

        $deletedIssue = $em->getRepository('IssuesBundle\Entity\Issue')
            ->find($issue->getId());

        if ($deletedIssue->isDeleted()) {
            $this->addFlash(
                'success',
                $this->get('translator')
                    ->trans('issues.issue.flashMessages.delete.success')
            );
        }

        return $this->redirectToRoute('issues.issues_index');
    }
}
