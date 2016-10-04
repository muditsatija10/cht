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

class LoadTokenData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        /** @var User $user */
        $user = $this->getReference('user-admin');

        $token = new Token();
        $token
            ->setToken('test')
            ->setUser($user)
            ;

        $em->persist($token);
        $em->flush();

        $this->addReference('ITGUser-token-admin', $token);

        // Create a user with no permissions and login
        $userNoPerm = new User();
        $userNoPerm
            ->setUsername('noperm')
            ->setPassword('$2a$06$gbf1lhr1BoGKpmSV82N1/OeMnoxXtdj3orOtJgCCQ5siuh8/5tZOC')
            ->setAvatar('images/avatars/default.png')
        ;
        $em->persist($userNoPerm);
        $this->addReference('ITGUser-user-noperm', $userNoPerm);

        $tokenNoPerm = new Token();
        $tokenNoPerm
            ->setToken('noperm')
            ->setUser($userNoPerm)
        ;
        $em->persist($tokenNoPerm);
        $this->addReference('ITGUser-token-noperm', $tokenNoPerm);

        $em->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 200;
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