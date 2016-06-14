<?php

namespace IssuesBundle\Tests\Unit\DependencyInjection;

use IssuesBundle\DependencyInjection\IssuesExtension;
use Oro\Bundle\TestFrameworkBundle\Test\DependencyInjection\ExtensionTestCase;

class IssuesExtensionTest extends ExtensionTestCase
{
    /**
     * Test Extension
     */
    public function testExtension()
    {
        $extension = new IssuesExtension();

        $this->loadExtension($extension);

        $this->assertDefinitionsLoaded([
            'issues_bundle.importexport.template_fixture.issue'
        ]);

        $this->assertEquals('issues', $extension->getAlias());
    }
}

