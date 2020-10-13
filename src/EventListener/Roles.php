<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security as SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User;
//use Symfony\Component\Security\Core\Authentication\Token\Storage as Storage;


class Roles {
    private $container;
    private $context;
    private $storage;

    public function __construct(Container $container, SecurityContext $context, TokenStorage $storage) {
        $this->container = $container;
        $this->context = $context;
        $this->storage = $storage;
    }

    public function onKernelController() {
        if($this->context->getToken()->getUser() instanceof User) {

            //Custom logic to see if you need to update the role or not.

            $user = $this->context->getToken()->getUser();

            //Update roles
            $user->addRole('ROLE_DOS_EM');

            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();


            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());

            $this->context->setToken($token);
        }
    }
}