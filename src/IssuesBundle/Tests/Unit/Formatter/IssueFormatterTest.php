<?php

namespace IssuesBundle\Tests\Unit\Formatter;

use IssuesBundle\Formatter\IssueFormatter;
use IssuesBundle\Model\Service\IssueTypesDefinition;

class IssueFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueFormatter
     */
    private $formatter;

    public function setUp()
    {
        $issueTypeDefinition = new IssueTypesDefinition();

        $this->formatter = new IssueFormatter($issueTypeDefinition);
    }

    public function testReturnsValidTypeCallback()
    {
        $this->assertInstanceOf('\Closure', $this->formatter->getTypeCallback());
    }
}
