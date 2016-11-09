<?php

namespace AppBundle\Service;

use AppBundle\Entity\Token;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use ITG\MillBundle\Security\GuidGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var GuidGenerator
     */
    private $guidGenerator;

    /**
     * UserService constructor.
     * @param $entityManager
     * @param $passwordEncoder
     * @param $guidGenerator
     */
    public function __construct($entityManager, $passwordEncoder, $guidGenerator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->guidGenerator = $guidGenerator;
    }

    /**
     * @param $email
     * @param $password
     * @return User
     */
    public function createUser($email, $password)
    {
        $user = new User();

        $user->setUsername($email);
        $user->setEmail($email);
        $user->setAvatar('');
        $user->setRegistered(new \DateTime());
        $user->setRoles([]);

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


    /**
     * @param $user
     * @return Token
     */
    public function getToken($user)
    {
        $token = new Token();
        $token->setUser($user)
            ->setToken($this->guidGenerator->generate())
            ->setActivated(new \DateTime());

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }
}