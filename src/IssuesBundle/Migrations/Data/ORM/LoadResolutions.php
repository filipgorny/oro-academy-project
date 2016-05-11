<?php

namespace IssuesBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IssuesBundle\Entity\Priority;
use IssuesBundle\Entity\Resolution;

class LoadResolutions extends AbstractFixture
{
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
