<?php

namespace IssuesBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
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
        $organization = $manager
            ->getRepository('OroOrganizationBundle:Organization')
            ->getFirst();

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
    }
}
