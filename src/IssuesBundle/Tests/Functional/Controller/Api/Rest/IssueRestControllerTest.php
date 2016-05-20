<?php


namespace IssuesBundle\Tests\Functional\Controller\Api\Rest;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Priority;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Router;

/**
 * @dbIsolation
 */
class IssueRestControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadUserData',
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testGetListAction()
    {
        $this->client->request('GET', $this->getUrl('issues_api_get_issue_list', ['id' => 1]));

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $responseData = $this->getDecodedResponse();

        $this->assertTrue(count($responseData) > 0);
    }

    public function testGetIssueItemAction()
    {
        /** @var Issue $issue */
        $issue = $this->loadIssue();

        $url = $this->getUrl('issues_api_get_issue', ['id' => $issue->getId()]);
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $responseData = $this->getDecodedResponse();

        $this->assertEquals($issue->getId(), $responseData->id);
        $this->assertEquals($issue->getSummary(), $responseData->summary);
        $this->assertEquals($issue->getCode(), $responseData->code);
        $this->assertEquals($issue->getDescription(), $responseData->description);
        $this->assertEquals($issue->getType(), $responseData->type);
        $this->assertEquals($issue->getCreatedAt(), new \DateTime($responseData->createdAt));
        $this->assertEquals($issue->getUpdatedAt(), new \DateTime($responseData->updatedAt));
        $this->assertEquals($issue->getPriority()->getName(), $responseData->priority);
    }

    public function testCreateAction()
    {
        $priority = $this->loadPriority();

        $requestData = [
            'summary' => 'test summary',
            'description' => 'test description',
            'priority' => $priority->getId(),
            'type' => Issue::TYPE_STORY
        ];

        $this->client->request('POST', $this->getUrl('issues_api_post_issue'), $requestData);

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $createdIssue = $this->loadIssue(['summary' => $requestData['summary']]);

        $this->assertEquals($createdIssue->getSummary(), $requestData['summary']);
        $this->assertEquals($createdIssue->getDescription(), $requestData['description']);
        $this->assertEquals($createdIssue->getPriority()->getId(), $requestData['priority']);
        $this->assertEquals($createdIssue->getType(), $requestData['type']);
    }


    public function xtestUpdateAction()
    {
        $priority = $this->loadPriority();

        /** @var Issue $issue */
        $issue = $this->getIssueRepository()->findOneBy([]);

        $requestData = [
            'summary' => 'Test API update issue method summary',
            'description' => 'Test API update issue method description',
            'priority' => $priority->getId()
        ];
        $this->client->request('PUT', '/api/rest/latest/bug-tracker/issues/' . $issue->getId(), $requestData);

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        /** @var Issue $updatedIssue */
        $updatedIssue = $this->loadIssue([]);

        $this->assertEquals($updatedIssue->getSummary(), $requestData['summary']);
        $this->assertEquals($updatedIssue->getDescription(), $requestData['description']);
        $this->assertEquals($updatedIssue->getPriority()->getId(), $requestData['priority']);
        $this->assertNotNull($updatedIssue->getType());
    }

    public function xtestDeleteIssue()
    {
        $issue = $this->getIssueRepository()->findOneBy([]);

        $this->client->request('DELETE', '/api/rest/latest/bug-tracker/issues/' . $issue->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $issueAfterDelete = $issue = $this->getIssueRepository()->findOneBy(['id' => $issue->getId()]);

        $this->assertNull($issueAfterDelete);
    }

    /**
     * @return mixed
     */
    protected function getDecodedResponse()
    {
        return json_decode($this->client->getResponse()->getContent());
    }

    /**
     * @param array $where
     * @return Issue
     */
    private function loadIssue(array $where = array())
    {
        $issue = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('IssuesBundle:Issue')
            ->findOneBy($where);

        if ($issue === null) {
            throw new \DomainException('Could not find the issue.');
        }

        return $issue;
    }

    /**
     * @return Priority
     */
    private function loadPriority()
    {
        $priority = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('IssuesBundle:Priority')
            ->findOneBy([]);

        if ($priority === null) {
            throw new \DomainException('Could not find any priority to use in tests.');
        }

        return $priority;
    }
}
