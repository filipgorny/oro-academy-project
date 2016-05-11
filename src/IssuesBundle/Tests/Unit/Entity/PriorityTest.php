<?php

namespace IssuesBundle\Tests\Unit\Entity;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestTrait;

    public function testSettingAndGettingValues()
    {
        $this->assertSettingAndGettingValuesKeepsConsistency('IssuesBundle\Entity\Priority');
    }
}
