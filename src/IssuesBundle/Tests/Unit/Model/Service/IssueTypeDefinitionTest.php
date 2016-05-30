<?php

namespace IssuesBundle\Tests\Unit\Model\Service;

use IssuesBundle\Model\Service\IssueTypesDefinition;

class IssueTypeDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypes()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $issueTypesDefinition = new IssueTypesDefinition($translator);

        $this->assertNotEmpty($issueTypesDefinition->getTypes());
        $this->assertNotEmpty($issueTypesDefinition->getTypesDictionary());
        $this->assertNotEmpty($issueTypesDefinition->getTypesDictionaryChoicesForNewEntries());
    }

    public function testTranslatesType()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnValue('translated'));

        $issueTypesDefinition = new IssueTypesDefinition($translator);

        $this->assertEquals($issueTypesDefinition->translateType(1), 'translated');
    }
}
