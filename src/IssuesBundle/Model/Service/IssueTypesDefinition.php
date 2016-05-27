<?php

namespace IssuesBundle\Model\Service;

/**
 * Class IssueTypesDefinition
 * @package IssuesBundle\Model\Service
 */
class IssueTypesDefinition
{
    const TYPE_BUG = 1;
    const TYPE_SUBTASK = 2;
    const TYPE_STORY = 3;

    /**
     * @return array
     */
    public function getTypes()
    {
        return [self::TYPE_BUG, self::TYPE_SUBTASK, self::TYPE_STORY];
    }

    /**
     * @return array
     */
    public function getTypesDictionary()
    {
        return [
            self::TYPE_BUG => 'bug',
            self::TYPE_STORY => 'story',
            self::TYPE_SUBTASK => 'subtask',
        ];
    }

    /**
     * @return array
     */
    public static function getTypesDictionaryChoicesForNewEntries()
    {
        return [
            self::TYPE_BUG => 'bug',
            self::TYPE_STORY => 'story',
        ];
    }

    /**
     * @param $type
     * @return string|null
     */
    public function translateType($type)
    {
        if (array_key_exists($type, $this->getTypesDictionary())) {
            return $this->getTypesDictionary()[$type];
        }
    }
}
