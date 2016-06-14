<?php

namespace IssuesBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\IssueTypesDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class IssueType extends AbstractType
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder
            ->add(
                'parent',
                'entity',
                [
                    'class' => 'IssuesBundle\Entity\Issue',
                    'label' => 'issues.issue.parent.label',
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->where('i.type = :type')
                            ->andWhere('i.deleted = :deleted')
                            ->orderBy('i.summary', 'ASC')
                            ->setParameters([
                                'type' => IssueTypesDefinition::TYPE_STORY,
                                'deleted' => false
                            ]);
                    }
                ]
            )
            ->add(
                'type',
                'choice',
                [
                    'required' => true,
                    'label' => 'issues.issue.type.label',
                    'choices' => $this->issueTypesDefinition->getTypesDictionaryChoicesForNewEntries(),
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
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) use ($entity) {
                        $parameters = [
                            'deleted' => false
                        ];

                        $qb = $er->createQueryBuilder('i')
                            ->andWhere('i.deleted = :deleted')
                            ->orderBy('i.summary', 'ASC');

                        if ($entity instanceof Issue && $entity->getId() > 0) {
                            $qb->andWhere('i.id <> :currentId');

                            $parameters['currentId'] = $entity->getId();
                        }

                        $qb->setParameters($parameters);

                        return $qb;
                    }
                ]
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'issue_type';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IssuesBundle\Entity\Issue'
        ));

        parent::setDefaultOptions($resolver);
    }
}
