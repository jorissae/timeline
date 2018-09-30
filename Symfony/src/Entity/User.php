<?php

namespace App\Entity;

use Idk\LegoBundle\Model\LegoUserModel;
use Doctrine\ORM\Mapping as ORM;
use Idk\LegoBundle\Annotation\Entity as Lego;

/**
 *
 * @Lego\Entity(config="Idk\LegoBundle\Configurator\DefaultUserConfigurator", title="Utilisateurs")
 * @ORM\Entity()
 */
class User extends LegoUserModel
{
}
