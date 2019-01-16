<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 19.11.2018
 * Time: 16:05
 */

namespace App\Controller;

use App\Service\Repository;
use App\Service\Vtb;
use App\Service\VtbApi;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Session\Session;

class AbstractCRUDController extends CRUDController
{
    /**
     * @return Session
     */
    protected function getSession()
    {
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
        return $this->get(Repository::class);
    }
}