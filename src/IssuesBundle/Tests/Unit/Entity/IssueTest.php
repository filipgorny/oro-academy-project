<?php

namespace IssuesBundle\Tests\Unit\Entity;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueTypesDefinition;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestTrait;
    
    public function testSettingAndGettingValues()
    {
        $this->assertSettingAndGettingValuesKeepsConsistency('IssuesBundle\Entity\Issue');

        $issue = new Issue();
        $issue->setDeleted(true);

        $this->assertTrue($issue->isDeleted());
    }

    public function testReturnsValidLabel()
    {
        $issue = new Issue();

        $issue->setCode('11');
        $issue->setSummary('test');

        $this->assertNotEmpty(strstr($issue->getLabel(), 'test'));
        $this->assertNotEmpty(strstr($issue->getLabel(), '11'));
    }

    public function testAddingChildPopulatesParentField()
    {
        $issue = new Issue();
        $childIssue = new Issue();

        $issue->addChild($childIssue);

        $this->assertEquals($childIssue->getParent(), $issue);

        $issue = new Issue();
        $childIssue = new Issue();

        $issue->setChildren([$childIssue]);

        $this->assertEquals($childIssue->getParent(), $issue);
    }

    public function testConvertsToString()
    {
        $issue = new Issue();

        $issue->setSummary('test');

        $this->assertTrue((bool)strstr((string)$issue, 'test'));
    }

    public function testStoriesMayHaveSubtasks()
    {
        $issue = new Issue();
        $issue->setType(IssueTypesDefinition::TYPE_BUG);

        $this->assertFalse($issue->mayHaveSubtasks());

        $issue->setType(IssueTypesDefinition::TYPE_STORY);

        $this->assertTrue($issue->mayHaveSubtasks());
    }
}
