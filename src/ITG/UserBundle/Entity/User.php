<?php

namespace ITG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JMS;

class User implements UserInterface, EquatableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Groups({"id"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @JMS\Groups({"user_list", "user_username"})
     *
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @JMS\Exclude
     */
    protected $password;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"user_list", "user_email"})
     */
    protected $email;

    /**
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     *
     * @JMS\Accessor(getter="getRolesOnly")
     * @JMS\Groups({"user_details", "user_roles"})
     */
    protected $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\RoleSet")
     * @ORM\JoinTable(name="users_role_sets",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="role_set_id", referencedColumnName="id")}
     * )
     *
     * @JMS\Groups({"user_details", "user_role_sets"})
     */
    protected $roleSets;

    /**
     * @ORM\Column(name="registered", type="datetime", nullable=true)
     *
     * @JMS\Groups({"user_list", "user_registered"})
     */
    protected $registered;

    /**
     * @ORM\Column(name="avatar", type="string")
     *
     * @JMS\Groups({"user_list", "user_avatar"})
     */
    protected $avatar = 'images/avatars/default.png';


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        // TODO: Implement isEqualTo() method.
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $sets = $this->roleSets;
        foreach ($sets as $set)
        {
            $roles = array_merge($roles, $set->getRoles());
        }
        return $roles;
    }

    public function getRolesOnly()
    {
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roleSets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Add roleSet
     *
     * @param \AppBundle\Entity\RoleSet $roleSet
     *
     * @return User
     */
    public function addRoleSet(\AppBundle\Entity\RoleSet $roleSet)
    {
        $this->roleSets[] = $roleSet;

        return $this;
    }

    /**
     * Remove roleSet
     *
     * @param \AppBundle\Entity\RoleSet $roleSet
     */
    public function removeRoleSet(\AppBundle\Entity\RoleSet $roleSet)
    {
        $this->roleSets->removeElement($roleSet);
    }

    /**
     * Get roleSets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoleSets()
    {
        return $this->roleSets;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     *
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
