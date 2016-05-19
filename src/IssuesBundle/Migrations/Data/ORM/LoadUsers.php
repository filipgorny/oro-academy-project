<?php

namespace IssuesBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsers extends AbstractFixture implements
    ContainerAwareInterface,
    DependentFixtureInterface
{
    const USER_FIRST_USERNAME = 'issues_test_user_1';
    const USER_SECOND_USERNAME = 'issues_test_user_2';
    const PASSWORD = 'sample_password';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('oro_user.manager');
        $organization = $this->getReference('default_organization');

        $users = [
            self::USER_FIRST_USERNAME => ['firstname' => 'John', 'lastname' => 'Smith'],
            self::USER_SECOND_USERNAME => ['firstname' => 'Alice', 'lastname' => 'Jobs']
        ];

        $group = $manager->getRepository('OroUserBundle:Group')
            ->findOneBy(array('name' => 'Administrators'));

        $role = $manager->getRepository('OroUserBundle:Role')
            ->findOneBy(array('role' => 'ROLE_ADMINISTRATOR'));

        foreach ($users as $userName => $userData) {
            $user = $userManager->createUser();
            $user->setUsername($userName)
                ->setPlainPassword(self::PASSWORD)
                ->setEmail($userName.'@example.com')
                ->setFirstName($userData['firstname'])
                ->setLastName($userData['lastname'])
                ->setOrganization($organization)
                ->addOrganization($organization)
                ->setEnabled(true);

            $user->addGroup($group);
            $user->addRole($role);

            $userManager->updateUser($user);

            $this->setReference($user->getUsername(), $user);
        }

        //$this->changeAdminPassword($manager);
    }

    private function changeAdminPassword(ObjectManager $manager)
    {
        $userManager = $this->container->get('oro_user.manager');

        $admin = $manager->getRepository('OroUserBundle:User')
            ->findOneBy(array('username' => 'admin'));

        if ($admin === null) {
            throw new \DomainException('Admin user not found.');
        }

        $admin->setPlainPassword('admin123');

        $userManager->updateUser($admin, true);
    }

    public function getDependencies()
    {
        return [
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData',
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadGroupData',
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData'
        ];
    }
}
