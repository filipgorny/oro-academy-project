<?php

namespace IssuesBundle\Model\Service;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class IssueTypesDefinition
 * @package IssuesBundle\Model\Service
 */
class IssueTypesDefinition
{
    const TYPE_BUG = 1;
    const TYPE_SUBTASK = 2;
    const TYPE_STORY = 3;
    const TYPE_TASK = 4;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * IssueTypesDefinition constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
            self::TYPE_BUG => $this->translateTypeName('bug'),
            self::TYPE_TASK => $this->translateTypeName('task'),
            self::TYPE_STORY => $this->translateTypeName('story'),
            self::TYPE_SUBTASK => $this->translateTypeName('subtask')
        ];
    }

    /**
     * @return array
     */
    public function getTypesDictionaryChoicesForNewEntries()
    {
        return [
            self::TYPE_BUG => $this->translateTypeName('bug'),
            self::TYPE_TASK => $this->translateTypeName('task'),
            self::TYPE_STORY => $this->translateTypeName('story'),
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

    private function translateTypeName($typeName)
    {
        return $this->translator->trans('issues.types.'.$typeName);
    }
}
