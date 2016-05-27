<?php

namespace OroCRM\Bundle\TaskBundle\Tests\Functional\Controller;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Tests\Functional\DataFixtures\LoadUserData;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class IssuesControllersTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1]));

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadUserData',
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testView()
    {
        $issue = $this->getIssue();

        if (!$issue) {
            throw new \RuntimeException(
                'No issue found in database that can be used for testing.'
            );
        }

        $this->client->request(
            'GET',
            $this->getUrl('issues.issue_view', array('id' => $issue->getId()))
        );
        
        $issueTypeDefinition = $this->client->getContainer()->get('issues.model.issue_types_definition');

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains($issue->getLabel(), $result->getContent());
        $this->assertContains($issueTypeDefinition->translateType($issue->getType()), $result->getContent());
        $this->assertContains(
            $issue->getPriority()->getName(),
            $result->getContent()
        );
    }

    public function testViewShowParent()
    {
        $issue = $this->getIssueWithParent();

        if (!$issue) {
            throw new \RuntimeException(
                'No issue with parent found in '
                .'database that can be used for testing.'
            );
        }

        $this->client->request(
            'GET',
            $this->getUrl('issues.issue_view', array('id' => $issue->getId()))
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains(
            $issue->getParent()->getLabel(),
            $result->getContent()
        );
    }

    /**
     * @return Issue
     */
    private function getIssue()
    {
        $issue = $this->getReference('issue');

        return $issue;
    }

    /**
     * @return Issue
     */
    private function getIssueWithParent()
    {
        $issue = $this->getReference('issue_with_parent');

        return $issue;
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->getReference(LoadUserData::USER_FIRST_USERNAME);
    }

    public function testCreate()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('issues.issue_create')
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $tokenExtract = $crawler
            ->filter('form');

        $this->assertTrue($tokenExtract->count() > 0);
    }

    public function testUpdate()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl(
                'issues.issue_update',
                ['id' => $this->getIssue()->getId()]
            ),
            [],
            [],
            $this->generateBasicAuthHeader(
                LoadUserData::USER_FIRST_USERNAME,
                LoadUserData::PASSWORD
            )
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $tokenExtract = $crawler
            ->filter('form');

        $this->assertTrue($tokenExtract->count() > 0);
    }

    public function testAddingDialog()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl(
                'issues.issue_add_dialog',
                ['id' => $this->getUser()->getId()]
            ),
            [],
            [],
            $this->generateBasicAuthHeader(
                LoadUserData::USER_FIRST_USERNAME,
                LoadUserData::PASSWORD
            )
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $tokenExtract = $crawler
            ->filter('form');

        $this->assertTrue($tokenExtract->count() > 0);
    }
}
