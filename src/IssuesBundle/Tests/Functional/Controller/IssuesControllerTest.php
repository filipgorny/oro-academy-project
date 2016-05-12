<?php

namespace OroCRM\Bundle\TaskBundle\Tests\Functional\Controller;

use IssuesBundle\Entity\Issue;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class IssuesControllersTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
    }

    public function testView()
    {
        $issue = $this->getIssue();

        if (!$issue) {
            throw new \RuntimeException('No issue found in database that can be used for testing.');
        }

        $this->client->request(
            'GET',
            $this->getUrl('issues.issue_view', array('id' => $issue->getId()))
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains($issue->getLabel(), $result->getContent());
        $this->assertContains($issue->getTypeName(), $result->getContent());
        $this->assertContains($issue->getPriority()->getName(), $result->getContent());
    }

    public function testViewShowParent()
    {
        $issue = $this->getIssueWithParent();

        if (!$issue) {
            throw new \RuntimeException('No issue with parent found in database that can be used for testing.');
        }

        $this->client->request(
            'GET',
            $this->getUrl('issues.issue_view', array('id' => $issue->getId()))
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains($issue->getParent()->getLabel(), $result->getContent());
    }

    /**
     * @return Issue
     */
    private function getIssue()
    {
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $issue = $manager->getRepository('IssuesBundle\Entity\Issue')->findOneBy(['code' => 'TEST1']);

        return $issue;
    }

    /**
     * @return Issue
     */
    private function getIssueWithParent()
    {
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $qb = $manager->getRepository('IssuesBundle\Entity\Issue')->createQueryBuilder('i');

        $issue = $qb
            ->where('i.parent is not null')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();

        return $issue;
    }
}
