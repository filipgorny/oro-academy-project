<?php

namespace IssuesBundle\ImportExport\Converter;

use Oro\Bundle\ImportExportBundle\Converter\AbstractTableDataConverter;

class IssuesDataConverter extends AbstractTableDataConverter
{
    protected function getHeaderConversionRules()
    {
        return [
            'code' => 'code',
            'createdAt' => 'createdAt',
            'description' => 'description',
            'id' => 'id',
            'deleted' => 'deleted',
            'summary' => 'summary',
            'type' => 'type',
            'updatedAt',
            'assignee' => 'assignee:username',
            'organization' => 'organization:name',
            'reporter' => 'reporter:username'
        ];
    }

    protected function getBackendHeader()
    {
        return array_values($this->getHeaderConversionRules());
    }
}
