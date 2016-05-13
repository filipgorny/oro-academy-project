<?php

namespace IssuesBundle\Tests\Functional\DataFixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWorkflowDefinitions extends AbstractFixture implements ContainerAwareInterface
{
    const WORKFLOW_NAME = 'issue_resolving';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $listConfiguration = $this->container->get('oro_workflow.configuration.config.workflow_list');
        $configurationBuilder = $this->container->get('oro_workflow.configuration.builder.workflow_definition');

        $workflowConfiguration = Yaml::parse(file_get_contents(__DIR__ . '/../../../Resources/config/workflow.yml'));
        $workflowConfiguration = $listConfiguration->processConfiguration($workflowConfiguration);
        $workflowDefinitions = $configurationBuilder->buildFromConfiguration($workflowConfiguration);

        foreach ($workflowDefinitions as $workflowDefinition) {
            $manager->persist($workflowDefinition);

            $this->setReference('issues_workflow', $workflowDefinition);
        }

        $manager->flush();
    }
}
