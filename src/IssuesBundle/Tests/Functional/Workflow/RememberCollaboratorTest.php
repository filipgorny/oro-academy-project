<?php

namespace IssuesBundle\Test\Workflow;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Tests\Functional\DataFixtures\LoadUserData;
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
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testIfRunningWorkflowMarksTheUserAsCollaborantInTheIssue()
    {
        $this->logIn($this->getReference(LoadUserData::USER_SECOND_USERNAME));

        $issue = $this->getIssue();

        $workflow = $this->getWorkflowManager()->getWorkflow('issue_resolving');
        $workflowItem = $issue->getWorkflowItem();

        $this->getContainer()->get('doctrine.orm.entity_manager')->persist($workflowItem);

        $transition = $workflow->getTransitionManager()->getTransition('start_progress');
        $transition->transit($workflowItem);

        $this->logIn($this->getReference(LoadUserData::USER_FIRST_USERNAME));

        $transition = $workflow->getTransitionManager()->getTransition('resolve');
        $transition->transit($workflowItem);

        $this->assertTrue(
            $issue->getCollaborators()->contains($this->getReference(LoadUserData::USER_SECOND_USERNAME))
        );

        $this->assertEquals(2, $issue->getCollaborators()->count());
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
