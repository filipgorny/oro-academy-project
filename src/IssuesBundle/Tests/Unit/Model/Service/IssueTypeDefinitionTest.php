<?php

namespace IssuesBundle\Tests\Unit\Model\Service;

use IssuesBundle\Model\Service\IssueTypesDefinition;

class IssueTypeDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypes()
    {
        $issueTypesDefinition = new IssueTypesDefinition();

        $this->assertNotEmpty($issueTypesDefinition->getTypes());
        $this->assertNotEmpty($issueTypesDefinition->getTypesDictionary());
        $this->assertNotEmpty($issueTypesDefinition->getTypesDictionaryChoicesForNewEntries());
    }

    public function testTranslatesType()
    {
        $issueTypesDefinition = new IssueTypesDefinition();

        $this->assertEquals($issueTypesDefinition->translateType(1), 'bug');
    }
}
