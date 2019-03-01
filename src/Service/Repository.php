<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 22.10.2018
 * Time: 15:54
 */

namespace App\Service;

use App\Application\Sonata\UserBundle\Entity\Group;
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
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getEntityManager();
        if (!$em->isOpen()) {
            $em = $em->create($em->getConnection(), $em->getConfiguration());
        }
        return $em;
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
     * @return EntityRepository
     */
    public function getUser()
    {
        return $this->getRepository(User::class);
    }

    /**
     * @return User
     */
    public function getReferenceUser($id)
    {
        return $this->getEm()->getReference(User::class, $id);
    }

    /**
     * @return EntityRepository
     */
    public function getGroup()
    {
        return $this->getRepository(Group::class);
    }

    /**
     * @return Group
     */
    public function getReferenceGroup($id)
    {
        return $this->getEm()->getReference(Group::class, $id);
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
