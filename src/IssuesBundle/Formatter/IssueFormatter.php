<?php

namespace IssuesBundle\Formatter;

use IssuesBundle\Model\Service\IssueTypesDefinition;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;

/**
 * Class IssueFormatter
 * @package IssuesBundle\Formatter
 */
class IssueFormatter
{
    /**
     * @var IssueTypesDefinition
     */
    private $issueTypesDefinition;

    /**
     * IssueType constructor.
     * @param IssueTypesDefinition $issueTypesDefinition
     */
    public function __construct(IssueTypesDefinition $issueTypesDefinition)
    {
        $this->issueTypesDefinition = $issueTypesDefinition;
    }

    /**
     * @return \Closure
     */
    public function getTypeCallback()
    {
        $formatter = $this;

        return function (ResultRecordInterface $record) use ($formatter) {
            $type = $record->getValue('type');

            return $formatter->issueTypesDefinition->translateType($type);
        };
    }
}
