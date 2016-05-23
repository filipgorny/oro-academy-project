<?php

namespace IssuesBundle\ImportExport\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;
use OroCRM\Bundle\ContactBundle\Entity\Contact;
use OroCRM\Bundle\ContactBundle\Formatter\SocialUrlFormatter;
use OroCRM\Bundle\ContactBundle\Model\Social;

class IssueNormalizer extends ConfigurableEntityNormalizer
{
    const ENTITY_CLASS = 'IssuesBundle\Entity\Issue';

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $result = parent::normalize($object, $format, $context);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return parent::denormalize($data, $class, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = array())
    {
        $class = self::ENTITY_CLASS;

        return $data instanceof $class;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return is_array($data) && $type == static::ENTITY_CLASS;
    }
}
