<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 26.09.2018
 * Time: 10:09
 */

namespace App\Command;

use App\Service\Repository;
use BackupManager\Manager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Store\FlockStore;

abstract class BaseCommand extends ContainerAwareCommand
{
    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        if (!$em->isOpen()) {
            $em = $em->create($em->getConnection(), $em->getConfiguration());
        }
        return $em;
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
     * @return \Symfony\Component\Lock\Lock
     */
    protected function createLock()
    {
        $flockStore = new FlockStore(sys_get_temp_dir());
        $factory = new Factory($flockStore);
        return $factory->createLock($this->getName());
    }

    /**
     * @return Manager
     */
    protected function getBackupManager()
    {
        return $this->getContainer()->get('backup_manager');
    }

    /**
     * @return Repository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get(Repository::class);
    }
}