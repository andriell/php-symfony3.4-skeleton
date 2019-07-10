<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 19.11.2018
 * Time: 15:48
 */

namespace App\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

class PhpInfoAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'phpinfo';
    protected $baseRouteName = 'phpinfo';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}
