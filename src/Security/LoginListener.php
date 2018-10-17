<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 27.09.2018
 * Time: 16:34
 */
class LoginListener
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * LoginListener constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @return Session
     */
    public function getSession() {
        return $this->container->get('session');
    }


    public function onLogin(InteractiveLoginEvent $e) {
        $req = $e->getRequest();
        $captchaRequest = strtolower(trim($req->get('_captcha')));
        $captchaSession = strtolower(trim($this->getSession()->get('CAPTCHA_LOGIN')));
        $this->getSession()->remove('CAPTCHA_LOGIN');
        if ($captchaRequest != $captchaSession) {
            throw new AuthenticationException('Captcha is incorrect');
        }
    }
}