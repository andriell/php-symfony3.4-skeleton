<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 26.09.2018
 * Time: 10:09
 */

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class BaseCommand extends ContainerAwareCommand
{
    /**
     * @return EntityManager
     */
    protected function getEm() {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }

}