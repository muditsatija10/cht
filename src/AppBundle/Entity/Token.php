<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 *
 * @ORM\Table(name="token")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TokenRepository")
 */
class Token extends \ITG\UserBundle\Entity\Token
{
}
