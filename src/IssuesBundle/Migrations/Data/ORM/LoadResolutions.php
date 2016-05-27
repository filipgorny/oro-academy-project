<?php

namespace IssuesBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use IssuesBundle\Entity\Resolution;

/**
 * Class LoadResolutions
 * @package IssuesBundle\Migrations\Data\ORM
 */
class LoadResolutions extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $resolutions = [
            'fixed',
            'invalid',
            'won\'t fix',
            'duplicate',
            'works for me',
            'incomplete'
        ];

        foreach ($resolutions as $name) {
            $resolution = new Resolution();
            $resolution->setName($name);

            $manager->persist($resolution);
        }

        $manager->flush();
    }
}
