<?php

namespace IssuesBundle\ImportExport\TemplateFixture;

use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueTypesDefinition;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

/**
 * Class IssueFixture
 * @package IssuesBundle\ImportExport\TemplateFixture
 */
class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'IssuesBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('TEST-123');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string  $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');

        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'TEST-123':
                $entity->setCode('TEST-123');
                $entity->setSummary('Summary');
                $entity->setDescription('Description');
                $entity->setCreatedAt(new \DateTime());
                $entity->setAssignee($userRepo->getEntity('John Doo'));
                $entity->setReporter($userRepo->getEntity('John Doo'));
                $entity->setType(IssueTypesDefinition::TYPE_BUG);
                $entity->setOrganization($organizationRepo->getEntity('default'));

                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
