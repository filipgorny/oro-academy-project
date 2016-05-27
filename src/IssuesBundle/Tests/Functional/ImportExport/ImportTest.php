<?php

namespace IssuesBundle\Test\Functional;

use Oro\Bundle\ImportExportBundle\Job\JobExecutor;
use Oro\Bundle\ImportExportBundle\Processor\ProcessorRegistry;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class ImportTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'IssuesBundle\\Tests\\Functional\\DataFixtures\\LoadIssuesData',
        ]);
    }

    public function testImport()
    {
        $strategy = 'issues.add_or_replace_issue';
        $entityClass = 'IssuesBundle\Entity\Issue';

        $this->getUrl(
            'oro_importexport_import_form',
            array(
                'entity'           => $entityClass,
                '_widgetContainer' => 'dialog'
            )
        );

        $crawler = $this->client->request(
            'GET',
            $this->getUrl(
                'oro_importexport_import_form',
                array(
                    'entity'           => $entityClass,
                    '_widgetContainer' => 'dialog'
                )
            )
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('entity='.urlencode($entityClass), $result->getContent());

        $file = $this->getImportTemplate();
        $this->assertTrue(file_exists($file));

        /** @var Form $form */
        $form = $crawler->selectButton('Submit')->form();

        /** TODO Change after BAP-1813 */
        $form->getFormNode()->setAttribute(
            'action',
            $form->getFormNode()->getAttribute('action') . '&_widgetContainer=dialog'
        );

        $form['oro_importexport_import[file]']->upload($file);
        $form['oro_importexport_import[processorAlias]'] = $strategy;

        $this->client->followRedirects(true);
        $this->client->submit($form);

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $crawler = $this->client->getCrawler();

        $this->assertEquals(0, $crawler->filter('.import-errors')->count());

        $this->client->followRedirects(false);

        $this->client->request(
            'GET',
            $this->getUrl(
                'oro_importexport_import_process',
                array(
                    'processorAlias' => $strategy,
                    '_format'        => 'json'
                )
            )
        );

        $data = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals(
            array(
                'success'   => true,
                'message'   => 'File was successfully imported.',
                'errorsUrl' => null
            ),
            $data
        );
    }

    /**
     * @return string
     */
    protected function getImportTemplate()
    {
        $result = $this
            ->getContainer()
            ->get('oro_importexport.handler.export')
            ->getExportResult(
                JobExecutor::JOB_EXPORT_TEMPLATE_TO_CSV,
                'issues',
                ProcessorRegistry::TYPE_EXPORT_TEMPLATE
            );

        $chains = explode('/', $result['url']);

        return $this
            ->getContainer()
            ->get('oro_importexport.file.file_system_operator')
            ->getTemporaryFile(end($chains))
            ->getRealPath();
    }
}
