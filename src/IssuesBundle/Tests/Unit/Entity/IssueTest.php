<?php

namespace IssuesBundle\Tests\Unit\Entity;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestTrait;
    
    public function testSettingAndGettingValues()
    {
        $this->assertSettingAndGettingValuesKeepsConsistency('IssuesBundle\Entity\Issue');
    }
}
