<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 19.11.2018
 * Time: 15:52
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;

class PhpInfoCRUDController extends AbstractCRUDController
{
    public function listAction()
    {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
        ob_start();
        phpinfo();
        $r = ob_get_contents();
        ob_get_clean();
        return new Response($r);
    }
}
