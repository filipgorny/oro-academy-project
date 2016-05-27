<?php

namespace IssuesBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type used in API controllers
 *
 * Class IssueApiType
 * @package IssuesBundle\Form\Type
 */
class IssueApiType extends IssueType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'ownership_disabled' => true
            ]
        );
    }

    public function getName()
    {
        return 'issue_api_type';
    }
}
