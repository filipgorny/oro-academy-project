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
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $issueTypeDefinition = new IssueTypesDefinition($translator);

        $this->formatter = new IssueFormatter($issueTypeDefinition);
    }

    public function testReturnsValidTypeCallback()
    {
        $this->assertInstanceOf('\Closure', $this->formatter->getTypeCallback());
    }
}
