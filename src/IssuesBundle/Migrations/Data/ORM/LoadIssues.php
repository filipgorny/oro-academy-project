<?php

namespace IssuesBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Entity\Priority;
use IssuesBundle\Entity\Resolution;

class LoadIssues extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $issue = new Issue();

        $user = $manager->getRepository('Oro\Bundle\UserBundle\Entity\User')->findOneBy(['username' => 'admin']);

        if (!$user) {
            throw new \DomainException('Admin user not found, ensure that fixtures from UserBundle are executed first.');
        }

        $admin = $manager->getRepository('Oro\Bundle\UserBundle\Entity\User')->findOneBy(['username' => 'admin']);
        $organization = $admin->getOrganization();

        $issue->setOrganization($organization);
        $issue->setOwner($admin);

        $issue->setSummary('Example Issue');
        $issue->setCode('TEST1');
        $issue->setDescription('This is an example, autogenerated, issue.');
        $issue->setType(Issue::TYPE_BUG);
        $issue->setPriority($this->getReference('priority_10'));
        $issue->setReporter($admin);
        $issue->setAssignee($admin);
        $issue->setCreatedAt(new \DateTime('now'));
        $issue->setUpdatedAt(new \DateTime('now'));

        $manager->persist($issue);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            'IssuesBundle\Migrations\Data\ORM\LoadPriorities',
            'IssuesBundle\Migrations\Data\ORM\LoadResolutions',
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData'
        ];
    }
}