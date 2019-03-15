<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 20.09.2018
 * Time: 15:37
 */

namespace App\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityController extends AbstractController
{
    public function captchaAction()
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        $this->getSession()->set('CAPTCHA_LOGIN', $builder->getPhrase());
        $response = new StreamedResponse([$builder, 'output'], 200, ['Content-type' => 'image/jpeg']);
        return $response;
    }
}
