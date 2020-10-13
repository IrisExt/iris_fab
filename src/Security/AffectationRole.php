<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security as SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User;

class AffectationRole {
    private $container;
    private $context;
    private $storage;

    public function __construct(Container $container, SecurityContext $context, TokenStorageInterface $storage) {
        $this->container = $container;
        $this->context = $context;
        $this->storage = $storage;
    }

    public function changeRole($role)
    {
        if($this->context->getToken()->getUser() instanceof User) {

            $user = $this->context->getToken()->getUser();
            $user->addRole($role);
//            $em = $this->container->get('doctrine')->getManager();
//            $em->persist($user);
//            $em->flush();
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
            $this->storage->setToken($token);

        }

    }
}