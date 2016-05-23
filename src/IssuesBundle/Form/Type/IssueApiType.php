<?php

namespace IssuesBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use IssuesBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

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
