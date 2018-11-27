<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 26.09.2018
 * Time: 10:09
 */

namespace App\Command;

use BackupManager\Manager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class BaseCommand extends ContainerAwareCommand
{
    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    protected function getParameter($name, $default = null)
    {
        return $this->getContainer()->hasParameter($name) ? $this->getContainer()->getParameter($name) : $default;
    }

    /**
     * @return Manager
     */
    protected function getBackupManager()
    {
        return $this->getContainer()->get('backup_manager');
    }
}