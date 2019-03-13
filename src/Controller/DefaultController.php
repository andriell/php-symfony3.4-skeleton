<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 20.09.2018
 * Time: 15:29
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
        }
        return new Response('');
    }

    /**
     * @Route("/login")
     */
    public function loginAction()
    {
        return new RedirectResponse($this->generateUrl('sonata_user_admin_security_login'));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
        return new RedirectResponse($this->generateUrl('sonata_user_admin_security_logout'));
    }
}