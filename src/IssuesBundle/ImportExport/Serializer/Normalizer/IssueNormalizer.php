<?php

namespace IssuesBundle\ImportExport\Serializer\Normalizer;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;

class IssueNormalizer extends ConfigurableEntityNormalizer
{
    const ENTITY_CLASS = 'IssuesBundle\Entity\Issue';

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $result = parent::normalize($object, $format, $context);

        if (isset($result['deleted'])) {
            $result['deleted'] = $result['deleted'] ? 'yes' : 'no';
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $result = parent::denormalize($data, $class, $format, $context);

        return $result;
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
