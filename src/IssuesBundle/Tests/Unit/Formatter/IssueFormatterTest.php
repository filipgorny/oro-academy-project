<?php

namespace IssuesBundle\Tests\Unit\Formatter;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Formatter\IssueFormatter;

class IssueFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueFormatter
     */
    private $formatter;

    public function setUp()
    {
        $this->formatter = new IssueFormatter();
    }

    public function testReturnsValidTypeCallback()
    {
        $this->assertInstanceOf('\Closure', $this->formatter->getTypeCallback());
    }

    public function testReturnsDictionary()
    {
        $this->assertTrue(count($this->formatter->getTypesDictionary()) > 0);
    }

    public function testTranslatesType()
    {
        $this->assertEquals('bug', $this->formatter->translateType(Issue::TYPE_BUG));
    }
}
