<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 15.10.2018
 * Time: 11:24
 */

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AC;
use Symfony\Component\HttpFoundation\Session\Session;

class AbstractController extends AC
{
    /**
     * @return Session
     */
    public function getSession() {
        return $this->get('session');
    }
}