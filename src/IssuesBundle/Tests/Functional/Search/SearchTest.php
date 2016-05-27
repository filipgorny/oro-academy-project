<?php

namespace IssuesBundle\Tests\Functional\Search;

use IssuesBundle\Entity\Issue;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\SearchBundle\Engine\Indexer as SearchIndexer;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class SearchTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);

        $this->reindex();
    }

    private function reindex()
    {
        $class  = 'IssuesBundle\Entity\Issue';

        /** @var $searchEngine EngineInterface */
        $searchEngine = $this->getContainer()->get('oro_search.search.engine');

        $searchEngine->reindex($class);
    }

    public function testFindsIssue()
    {
        $issue = $this->getReference('issue');

        /** @var $searchIndexer SearchIndexer */
        $searchIndexer = $this->getContainer()->get('oro_search.index');

        $searchResult = $searchIndexer->simpleSearch(
            $issue->getSummary(),
            0,
            10
        );

        $found = false;

        foreach ($searchResult->getElements() as $result) {
            $entity = $result->getEntity();

            if ($entity instanceof Issue) {
                if ($entity->getSummary() == $issue->getSummary()) {
                    $found = true;
                }
            }
        }

        $this->assertTrue($found, 'Issue not found.');
    }
}
