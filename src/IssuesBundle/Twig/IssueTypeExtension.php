<?php

namespace IssuesBundle\Twig;

use IssuesBundle\Model\Service\IssueTypesDefinition;

class IssueTypeExtension extends \Twig_Extension
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
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('issue_type_name', array($this, 'issueTypeName')),
        );
    }

    /**
     * @param $type
     * @return null|string
     */
    public function issueTypeName($type)
    {
        return $this->issueTypesDefinition->translateType($type);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'issue_type_extension';
    }
}
