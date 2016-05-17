<?php

namespace IssuesBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

class IssuesBundleMigration implements Migration, NoteExtensionAwareInterface
{
    /**
     * @var NoteExtension
     */
    protected $noteExtension;

    public function up(Schema $schema, QueryBag $queries)
    {
        $this->createOroIssuesPrioritiesTable($schema);
        $this->createOroIssuesResolutionsTable($schema);
        $this->createOroIssuesTable($schema);

        $this->createIssuePivotTables($schema);

        $this->noteExtension->addNoteAssociation($schema, 'oro_issues');
    }

    /**
     * Create oro_issues table
     *
     * @param Schema $schema
     */
    protected function createOroIssuesTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issues');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('updated_by_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('description', 'string', ['length' => 255]);
        $table->addColumn('type', 'smallint', []);
        $table->addColumn('deleted', 'smallint', []);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['updated_by_id'], 'IDX_AADA29B1896DBBDE', []);
        $table->addIndex(['owner_id'], 'IDX_AADA29B17E3C61F9', []);
        $table->addIndex(['organization_id'], 'IDX_AADA29B132C8A3DE', []);
        $table->addIndex(['priority_id'], 'IDX_AADA29B1497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_AADA29B112A1C43A', []);
        $table->addIndex(['reporter_id'], 'IDX_AADA29B1E1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_AADA29B159EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_2DDC40DD727ACA70', []);
    }

    /**
     * Create oro_issues_priorities table
     *
     * @param Schema $schema
     */
    protected function createOroIssuesPrioritiesTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issues_priorities');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('level', 'integer', []);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create oro_issues_resolutions table
     *
     * @param Schema $schema
     */
    protected function createOroIssuesResolutionsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issues_resolutions');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * Create pivot tables for many to many relations in the Issue
     *
     * @param Schema $schema
     */
    protected function createIssuePivotTables(Schema $schema)
    {
        $table = $schema->createTable('oro_issues_related');
        $table->addColumn('parent_issue_id', 'integer', []);
        $table->addColumn('related_issue_id', 'integer', []);

        $table = $schema->createTable('oro_issues_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
    }
}
