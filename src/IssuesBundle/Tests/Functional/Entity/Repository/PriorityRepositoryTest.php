<?php

namespace IssuesBundle\Tests\Functional\Entity\Repository;

use IssuesBundle\Entity\Priority;
use IssuesBundle\Entity\Repository\PriorityRepository;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class PriorityRepositoryTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1]));

        $this->loadFixtures([
            'IssuesBundle\Migrations\Data\ORM\LoadPriorities',
        ]);
    }

    public function testFindAllAlphabetically()
    {
        /**
         * @var PriorityRepository $repository
         */
        $repository = $this->getContainer()->get('doctrine')->getRepository('IssuesBundle:Priority');

        $result = $repository->findAllAlphabetically();

        $this->assertTrue(count($result) > 0);

        $returnedValidPriority = false;

        foreach ($result as $r) {
            if ($r instanceof Priority) {
                $returnedValidPriority = true;

                break;
            }
        }

        $this->assertTrue($returnedValidPriority);
    }

    public function testFindAllAlphabeticallyArray()
    {
        /**
         * @var PriorityRepository $repository
         */
        $repository = $this->getContainer()->get('doctrine')->getRepository('IssuesBundle:Priority');

        $result = $repository->findAllAlphabeticallyArray();

        $this->assertTrue(count($result) > 0);

        $returnedValidPriority = false;

        foreach ($result as $id => $name) {
            if ($id > 0 && !empty($name)) {
                $returnedValidPriority = true;

                break;
            }
        }

        $this->assertTrue($returnedValidPriority);
    }
}
