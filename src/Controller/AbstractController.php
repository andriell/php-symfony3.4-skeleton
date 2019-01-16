<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 15.10.2018
 * Time: 11:24
 */

namespace App\Controller;

use App\Service\Repository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AC;
use Symfony\Component\HttpFoundation\Session\Session;

class AbstractController extends AC
{
    public static function getSubscribedServices()
    {
        $r = parent::getSubscribedServices();
        $r['app_repository'] = '?' . Repository::class;
        return $r;
    }

    /**
     * @return Session
     */
    public function getSession() {
        return $this->get('session');
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->get('doctrine')->getEntityManager();
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->getEm()->getConnection();
    }

    /**
     * @return Repository
     */
    protected function getRepository()
    {
        return $this->container->get('app_repository');
    }
}