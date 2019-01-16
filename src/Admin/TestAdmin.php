<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 19.11.2018
 * Time: 15:48
 */

namespace App\Admin;


use Sonata\AdminBundle\Route\RouteCollection;

class TestAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'test';
    protected $baseRouteName = 'test';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}