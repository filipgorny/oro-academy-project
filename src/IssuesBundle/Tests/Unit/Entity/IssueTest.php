<?php

namespace IssuesBundle\Tests\Unit\Entity;

use IssuesBundle\Entity\Issue;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestTrait;
    
    public function testSettingAndGettingValues()
    {
        $this->assertSettingAndGettingValuesKeepsConsistency('IssuesBundle\Entity\Issue');
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
}
