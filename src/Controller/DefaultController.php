<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 20.09.2018
 * Time: 15:29
 */
class DefaultController extends AbstractController
{
    public function indexAction()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
        }
        return new Response('');
    }

    public function infoAction()
    {
        ob_start();
        phpinfo();
        $r = ob_get_contents();
        ob_get_clean();
        return new Response($r);
    }

    public function loginAction()
    {
        return new RedirectResponse($this->generateUrl('sonata_user_admin_security_login'));
    }

    public function logoutAction()
    {
        return new RedirectResponse($this->generateUrl('sonata_user_admin_security_logout'));
    }
}