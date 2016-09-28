<?php

namespace ITG\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\RoleSet;
use AppBundle\Entity\Token;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        // Create role set
        $roleSet = new RoleSet();
        $roleSet
            ->setName('Admin')
            ->setRoles([
                'ITG_USER_LIST_USERS', 'ITG_USER_EDIT_USERS', 'ITG_USER_DELETE_USERS', 'ITG_USER_EDIT_ROLES'
            ]);
        $em->persist($roleSet);
        $this->addReference('user-role-admin', $roleSet);

        // Create user
        $user = new User();
        $user
            ->setUsername('admin')
            ->addRoleSet($roleSet)
            ->setPassword('$2a$06$gbf1lhr1BoGKpmSV82N1/OeMnoxXtdj3orOtJgCCQ5siuh8/5tZOC')
            ->setAvatar('images/avatars/default.png')
        ;
        $em->persist($user);

        $this->addReference('user-admin', $user);
        $em->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 100;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}