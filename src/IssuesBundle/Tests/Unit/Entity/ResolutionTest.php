<?php

namespace IssuesBundle\Tests\Unit\Entity;

use IssuesBundle\Entity\Resolution;

class ResolutionTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestTrait;

    public function testSettingAndGettingValues()
    {
        $this->assertSettingAndGettingValuesKeepsConsistency('IssuesBundle\Entity\Resolution');
    }

    public function testConvertsToString()
    {
        $resolution = new Resolution();

        $resolution->setName('test');

        $this->assertTrue((bool)strstr((string)$resolution, 'test'));
    }
}
