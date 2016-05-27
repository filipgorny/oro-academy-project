<?php

namespace IssuesBundle\Tests\Unit\Form\Type;

use Genemu\Bundle\FormBundle\Form\JQuery\Type\Select2Type;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Priority;
use IssuesBundle\Form\Type\IssueType;
use IssuesBundle\Model\Service\IssueTypesDefinition;
use Oro\Bundle\FormBundle\Form\Type\OroEntitySelectOrCreateInlineType;
use Oro\Bundle\FormBundle\Form\Type\OroJquerySelect2HiddenType;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Oro\Bundle\UserBundle\Form\Type\OrganizationUserAclSelectType;
use Oro\Bundle\UserBundle\Form\Type\UserAclSelectType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class IssueTypeTest
 * @package IssuesBundle\Tests\Unit\Form\Type
 *
 * @SuppressWarnings(PHPMD)
 */
class IssueTypeTest extends TypeTestCase
{
    private $validator;

    protected function getExtensions()
    {
        $this->validator = $this->getMock(
            'Symfony\Component\Validator\Validator\ValidatorInterface'
        );

        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));

        $this->validator
            ->method('getMetadataFactory')
            ->will($this->returnValue($this->validator));

        $metaData = $this->getMock(
            '\StdClass',
            ['addConstraint', 'addPropertyConstraint']
        );

        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue($metaData));


        return array(
            new ValidatorExtension($this->validator),
            new PreloadedExtension($this->loadTypes(), array()),
        );
    }

    /**
     * @return AbstractType[]
     */
    protected function loadTypes()
    {
        $securityFacade = $this->getMockBuilder(
            'Oro\Bundle\SecurityBundle\SecurityFacade'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $searchHandler = $this->getMock(
            'Oro\Bundle\FormBundle\Autocomplete\SearchHandlerInterface'
        );
        $searchHandler->expects($this->any())
            ->method('getEntityName')
            ->will($this->returnValue('OroUserBundle:User'));

        $searchRegistry = $this->getMockBuilder(
            'Oro\Bundle\FormBundle\Autocomplete\SearchRegistry'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $searchRegistry->expects($this->any())
            ->method('getSearchHandler')
            ->will($this->returnValue($searchHandler));

        $configProvider = $this->getMockBuilder(
            'Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $config = $this->getMockBuilder(
            'Oro\Bundle\EntityConfigBundle\Config\ConfigManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $metadata = $this
            ->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $identifier = 'id';

        $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilderChainMethods = ['where', 'orderBy', 'setParameters', 'getQuery', 'getSql'];

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->setMethods($queryBuilderChainMethods)
            ->setConstructorArgs([$entityManager])
            ->getMock();

        foreach ($queryBuilderChainMethods as $method) {
            $queryBuilder
                ->method($method)
                ->will($this->returnValue($queryBuilder));
        }

        $repository->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder));


        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository));


        $metadata->expects($this->any())
            ->method('getSingleIdentifierFieldName')
            ->will($this->returnValue($identifier));

        $configManager = $this->getMockBuilder(
            'Oro\Bundle\ConfigBundle\Config\ConfigManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $htmlTagProvider = $this->getMock(
            'Oro\Bundle\FormBundle\Provider\HtmlTagProvider'
        );

        $htmlTagProvider->expects($this->any())
            ->method('getAllowedElements')
            ->willReturn(['br', 'a']);

        $classMetadata = $this->getMockBuilder(
            '\Doctrine\Common\Persistence\Mapping\ClassMetadata'
        )
            ->setMethods([
                'getIdentifierFieldNames',
                'getTypeOfField',
                'getName',
                'getClassMetaData',
                'getSingleIdentifierFieldName',
                'getIdentifierValues'
            ])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $classMetadata->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(array('name')));

        $classMetadata->expects($this->any())
            ->method('getSingleIdentifierFieldName')
            ->will($this->returnValue('name'));

        $classMetadata->expects($this->any())
            ->method('getTypeOfField')
            ->will($this->returnValue('integer'));

        $classMetadata->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('IssuesBundle\Entity\Priority'));

        $classMetadata->expects($this->any())
            ->method('getIdentifierValues')
            ->will($this->returnValue(['test']));


        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($classMetadata));


        $mockRegistry = $this->getMockBuilder(
            'Doctrine\Bundle\DoctrineBundle\Registry'
        )
            ->disableOriginalConstructor()
            ->setMethods(array('getManagerForClass'))
            ->getMock();

        $mockRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($entityManager));


        $mockEntityType = $this->getMockBuilder(
            'Symfony\Bridge\Doctrine\Form\Type\EntityType'
        )
            ->setMethods(array('getName', 'getLoader'))
            ->setConstructorArgs(array($mockRegistry))
            ->getMock();

        $mockEntityType->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('entity'));


        $loader = $this->getMockBuilder(
            'Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $priority = new Priority();
        $priority->setName('test');

        $entityManager->persist($priority);
        $entities = [$priority];


        $entityManager
            ->method('contains')
            ->will($this->returnValue(true));

        $loader
            ->method('getEntitiesByIds')
            ->will($this->returnValue($entities));

        $loader
            ->method('getEntities')
            ->will($this->returnValue($entities));

        $mockEntityType
            ->method('getLoader')
            ->will($this->returnValue($loader));

        $mockEntityType
            ->method('configureOptions');


        $types = [
            new OrganizationUserAclSelectType(),
            new UserAclSelectType(),
            new OroEntitySelectOrCreateInlineType(
                $securityFacade,
                $config
            ),
            new OroJquerySelect2HiddenType(
                $entityManager,
                $searchRegistry,
                $configProvider
            ),
            new Select2Type('hidden'),
            new OroRichTextType($configManager, $htmlTagProvider),
            $mockEntityType
        ];

        $keys = array_map(
            function ($type) {
                /* @var AbstractType $type */
                return $type->getName();
            },
            $types
        );

        return array_combine($keys, $types);
    }

    public function testSubmitValidData()
    {
        $issueTypeDefinition = new IssueTypesDefinition();

        $issueType = new IssueType($issueTypeDefinition);

        $priority = new Priority();
        $priority->setName('test');

        $formData = array(
            'type' => IssueTypesDefinition::TYPE_BUG,
            'summary' => 'test test',
            'description' => 'description 123',
        );

        $form = $this->factory->create($issueType);

        $issue = new Issue();
        $issue->setType($formData['type']);
        $issue->setSummary($formData['summary']);
        $issue->setDescription($formData['description']);

        // TODO test rest of the fields
        // there is a problem with Entity type fields,
        // ie. priority doesn't work, but it is probably caused
        // by mocking entity manager and other services
        //

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertInstanceOf('IssuesBundle\Entity\Issue', $form->getData());

        $this->assertEquals($issue->getSummary(), $form->getData()->getSummary());
        $this->assertEquals($issue->getType(), $form->getData()->getType());
        $this->assertEquals($issue->getDescription(), $form->getData()->getDescription());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
