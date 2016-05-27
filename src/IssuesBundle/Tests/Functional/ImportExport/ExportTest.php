<?php

namespace IssuesBundle\Test\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $result);

        $issues = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IssuesBundle:Issue')
            ->createQueryBuilder('i')
            ->getQuery()
            ->getResult();

        if ($result instanceof BinaryFileResponse) {
            $fullFileName = $result->getFile()->getPath() . DIRECTORY_SEPARATOR . $result->getFile()->getFilename();

            $csv = array_map('str_getcsv', file($fullFileName));

            $found = 0;

            foreach ($issues as $issue) {
                foreach ($csv as $line) {
                    if ($line['0'] == $issue->getCode()) {
                        $found++;
                    }
                }
            }

            $this->assertEquals($found, count($issues));

            return;
        }

        $this->fail('Failed obtaining export file.');
    }
}
