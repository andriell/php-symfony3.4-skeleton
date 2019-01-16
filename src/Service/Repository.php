<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 22.10.2018
 * Time: 15:54
 */

namespace App\Service;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Repository
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * ContainerInterface constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->container->get('doctrine')->getEntityManager();
    }

    /**
     * @param $entityName
     * @return ObjectRepository|EntityRepository
     */
    protected function getRepository($entityName)
    {
        $repository = $this->getEm()->getRepository($entityName);
        if ($repository instanceof ContainerAwareInterface) {
            $repository->setContainer($this->container);
        }
        return $repository;
    }


    /**
     * @return UserRepository
     */
    public function getUser()
    {
        return $this->getRepository(User::class);
    }

    /**
     * @return User
     */
    public function getReferencePay($id)
    {
        return $this->getEm()->getReference(User::class, $id);
    }

    /**
     * @return PayStatus
     */
    public function getReferencePayStatus($id)
    {
        return $this->getEm()->getReference('App:PayStatus', $id);
    }

    /**
     * @return PayType
     */
    public function getReferencePayType($id)
    {
        return $this->getEm()->getReference('App:PayType', $id);
    }

    /**
     * @param callable $callable
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function transaction($parameters, $callable)
    {
        $em = $this->getEm();
        $em->beginTransaction();
        try {
            $r = call_user_func($callable, $parameters);
            $em->commit();
            return $r;
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }
}
