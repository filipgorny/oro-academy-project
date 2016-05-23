<?php

namespace IssuesBundle\Test\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class ExportTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testExport()
    {
        $this->client->followRedirects(false);

        $this->client->request(
            'GET',
            $this->getUrl(
                'oro_importexport_export_instant',
                array(
                    'processorAlias' => 'issues',
                    '_format'        => 'json'
                )
            )
        );

        $data = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertTrue($data['success']);
        $this->assertTrue($data['readsCount'] > 0);

        $this->client->request(
            'GET',
            $data['url']
        );

        $result = $this->client->getResponse();
        $this->assertResponseStatusCodeEquals($result, 200);
        $this->assertResponseContentTypeEquals($result, 'text/csv');

        $content = $this->client->getResponse()->getContent();
    }
}
