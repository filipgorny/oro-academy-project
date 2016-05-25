<?php


namespace IssuesBundle\Tests\Functional\Controller\Dashboard;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class DashboardControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testIssuesByStatusChartRoute()
    {
        $this->client->request(
            'GET',
            $this->getUrl('issues.issues_by_status_chart', ['widget' => 'issues_by_status_chart'])
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('bar-chart-component', $this->client->getResponse()->getContent());

        $steps = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('Oro\Bundle\WorkflowBundle\Entity\WorkflowStep')
            ->createQueryBuilder('step')
            ->join('Oro\Bundle\WorkflowBundle\Entity\WorkflowDefinition', 'definition')
            ->where('definition.name = :definitionName')
            ->setParameter('definitionName', 'issue_resolving')
            ->getQuery()
            ->getResult();

        $this->assertTrue(count($steps) > 0);

        foreach ($steps as $step) {
            $this->assertContains($step->getLabel(), $this->client->getResponse()->getContent());
        }
    }
}
