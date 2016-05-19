<?php

namespace IssuesBundle\Formatter;

use IssuesBundle\Entity\Issue;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;

class IssueFormatter
{
    /**
     * @return \Closure
     */
    public function getTypeCallback()
    {
        $formatter = $this;

        return function (ResultRecordInterface $record) use ($formatter) {
            $type = $record->getValue('type');

            return $formatter->translateType($type);
        };
    }

    /**
     * @return array
     */
    public function getTypesDictionary()
    {
        return Issue::getTypesDictionary();
    }

    /**
     * @param $type
     * @return string
     */
    public function translateType($type)
    {
        $types = $this->getTypesDictionary();

        if (isset($types[$type])) {
            return $types[$type];
        } else {
            return $type;
        }
    }
}
