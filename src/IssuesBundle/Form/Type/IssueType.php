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

class IssueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'parent',
                'entity',
                [
                    'class' => 'IssuesBundle\Entity\Issue',
                    'label' => 'issues.issue.parent.label',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->where('i.type = :type')
                            ->orderBy('i.summary', 'ASC')
                            ->setParameter('type', Issue::TYPE_STORY);
                    }
                ]
            )
            ->add(
                'type',
                'choice',
                [
                    'required' => true,
                    'label' => 'issues.issue.type.label',
                    'choices' => Issue::getTypesDictionaryChoicesForNewEntries(),
                    'constraints'   => new NotNull(),
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'issues.issue.summary.label',
                    'constraints'   => [
                        new NotBlank(),
                        new Length(['min' => 5, 'max' => '255'])
                    ]
                ]
            )
            ->add(
                'assignee',
                'oro_user_organization_acl_select',
                [
                    'label' => 'issues.issue.assignee.label'
                ]
            )
            ->add(
                'description',
                'oro_rich_text',
                [
                    'required' => true,
                    'label' => 'issues.issue.description.label',
                    'constraints'   => [
                        new NotBlank(),
                        new Length(['min' => 7])
                    ]
                ]
            )
            ->add(
                'priority',
                'entity',
                [
                    'class' => 'IssuesBundle\Entity\Priority',
                    'required' => true,
                    'label' => 'issues.issue.priority.label',
                    'constraints'   => [
                        new NotNull(),
                    ]
                ]
            )
            ->add(
                'relatedIssues',
                'entity',
                [
                    'class' => 'IssuesBundle\Entity\Issue',
                    'label' => 'issues.issue.relatedIssues.label',
                    'multiple' => true
                ]
            );
    }

    public function getName()
    {
        return 'issue_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IssuesBundle\Entity\Issue'
        ));

        parent::setDefaultOptions($resolver);
    }
}
