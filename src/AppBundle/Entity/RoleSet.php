<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RoleSet
 *
 * @ORM\Table(name="role_set")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleSetRepository")
 */
class RoleSet extends \ITG\UserBundle\Entity\RoleSet
{
}
