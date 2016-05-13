<?php

namespace IssuesBundle\Test\Workflow;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Tests\Functional\DataFixtures\LoadUserData;
use IssuesBundle\Tests\Functional\DataFixtures\LoadWorkflowDefinitions;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class RememberCollaboratorTest extends WebTestCase
{
    public function testIfRunningWorkflowMarksTheUserAsCollaborantInTheIssue()
    {
        $this->initClient();

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadUserData',
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadWorkflowDefinitions',
        ]);

        $this->logIn($this->getReference(LoadUserData::USER_SECOND_USERNAME));

        $issue = $this->getIssue();

        $workflowManager = $this->getWorkflowManager();

       // $workflowManager->activateWorkflow(LoadWorkflowDefinitions::WORKFLOW_NAME);

        $workflow = $workflowManager->getWorkflow(LoadWorkflowDefinitions::WORKFLOW_NAME);
        $workflowItem = $issue->getWorkflowItem();

        $this->getContainer()->get('doctrine.orm.entity_manager')->persist($workflowItem);

        $transition = $workflow->getTransitionManager()->getTransition('start_progress');
        $transition->transit($workflowItem);

        $this->logIn($this->getReference(LoadUserData::USER_FIRST_USERNAME));

        $transition = $workflow->getTransitionManager()->getTransition('resolve');
        $transition->transit($workflowItem);

        $this->assertTrue($issue->getCollaborators()->contains($this->getReference(LoadUserData::USER_SECOND_USERNAME)));
        $this->assertEquals(1, $issue->getCollaborators()->count());
    }

    /**
     * @return WorkflowManager
     */
    protected function getWorkflowManager()
    {
        return $this->client->getContainer()->get('oro_workflow.manager');
    }

    /**
     * @return Issue
     */
    private function getIssue()
    {
        $issue = $this->getReference('issue');

        return $issue;
    }

    private function logIn(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->getContainer()->get('security.context')->setToken($token);
        $this->getContainer()->get('session')->set('_security_secured_area', serialize($token));
    }
}
