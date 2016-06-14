<?php

namespace IssuesBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use IssuesBundle\Entity\Priority;

class LoadPriorities extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $priorites = [
            0  => 'trivial',
            10 => 'major',
            20 => 'critical',
            30 => 'blocker'
        ];

        foreach ($priorites as $level => $name) {
            $existing = $manager->getRepository('IssuesBundle\Entity\Priority')
                ->findOneBy(['name' => $name]);

            if (!($existing)) {
                $priority = new Priority();
                $priority->setLevel($level);
                $priority->setName($name);

                $this->setReference('priority_' . $level, $priority);

                $manager->persist($priority);

                continue;
            }

            $this->setReference('priority_' . $level, $existing);
        }

        $manager->flush();
    }
}
