<?php

namespace IssuesBundle\Controller;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueTypesDefinition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="issues.issues_index")
     * @Route("/report", name="issues.issues_index_report")
     * @Template
     */
    public function indexAction()
    {
        return [
            'entity_class' => 'IssuesBundle\Entity\Issue'
        ];
    }

    /**
     * @Route("/collaborated-recently", name="issues.issues_collaborated_recently")
     * @Template
     */
    public function collaboratedRecentlyAction()
    {
        return [
            'entity_class' => 'IssuesBundle\Entity\Issue'
        ];
    }

    /**
     * @param Issue $issue
     * @return array
     *
     * @Route("/view/{id}", name="issues.issue_view", requirements={"id"="\d+"})
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
        }

        return $this->updateAction($issue, $request);
    }

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

            $issue->setReporter($currentUser);

            if ($issue->getParent()) {
                $issue->setType(IssueTypesDefinition::TYPE_SUBTASK);
            }
            
            $this->get('issues.model.collaboration')->updateCollaborators($issue);
            
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
    public function deleteAction($id)
    {
        if ($this->get('issues.model.issue_deletion')->deleteIssueById($id)) {
            $this->addFlash(
                'success',
                $this->get('translator')
                    ->trans('issues.issue.flashMessages.delete.success')
            );
        }

        return $this->redirectToRoute('issues.issues_index');
    }

    /**
     * @Route("/add-issue-dialog/{id}", name="issues.issue_add_dialog",
     *     requirements={"id"="\d+"})
     * @Template
     */
    public function addIssueForUserDialogAction(User $assignee, Request $request)
    {
        $currentUser = $this->get('security.context')->getToken()->getUser();

        $issue = new Issue();
        $issue->setAssignee($assignee);
        $issue->setOwner($currentUser);

        /**
         * @var Form $form
         */
        $form = $this->get('form.factory')->create('issue_type', $issue);

        $saved = false;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /**
                 * @var $issue Issue
                 */
                $issue = $form->getData();

                $issue->setReporter($currentUser);

                $em = $this->getDoctrine()->getManager();

                $em->persist($issue);
                $em->flush();

                if ($issue->getId()) {
                    $saved = true;
                }
            }
        }

        return [
            'form' => $form->createView(),
            'saved' => $saved
        ];
    }
}
