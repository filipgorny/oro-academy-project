<?php

namespace IssuesBundle\Entity;

if (!class_exists('IssuesBundle\Entity\ExtendIssue')): // I had to add this cause of issue with oro:migration:load which causes
    // "PHP Fatal error:  Cannot redeclare class IssuesBundle\Entity\ExtendIssue"
    // I have tried changing name etc. but no luck
    // This bug happens any time I am extending an entity class (class Foo extends Bar)
    // TODO find the reason of this strange error
class ExtendIssue
{
    /**
     * Constructor
     *
     * The real implementation of this method is auto generated.
     *
     * IMPORTANT: If the derived class has own constructor it must call parent constructor.
     */
    public function __construct()
    {
    }
}
endif;