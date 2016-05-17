<?php

namespace IssuesBundle\Tests\Unit\Entity;

use IssuesBundle\Entity\Issue;

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

    public function testReturnsValidTypeName()
    {
        $issue = new Issue();

        $issue->setType(Issue::TYPE_BUG);

        $this->assertEquals($issue->getTypeName(), 'bug');

        $issue->setType(Issue::TYPE_STORY);

        $this->assertEquals($issue->getTypeName(), 'story');

        $issue->setType(Issue::TYPE_SUBTASK);

        $this->assertEquals($issue->getTypeName(), 'subtask');
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

    public function testReturnsTypeDictionary()
    {
        $dictionary = Issue::getTypesDictionary();

        $this->assertTrue(is_array($dictionary));
        $this->assertTrue(count($dictionary) > 1);
    }

    public function testReturnsTypesDictionaryChoicesForNewEntries()
    {
        $dictionary = Issue::getTypesDictionaryChoicesForNewEntries();

        $this->assertTrue(is_array($dictionary));
        $this->assertTrue(count($dictionary) > 1);
    }

    public function testConvertsToString()
    {
        $issue = new Issue();

        $issue->setSummary('test');

        $this->assertTrue((bool)strstr((string)$issue, 'test'));
    }
}
