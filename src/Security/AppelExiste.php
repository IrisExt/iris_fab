<?php

namespace App\Security;

use App\Entity\TgAppelProj;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AppelExiste
{

    private $router;
    private $session;
    private $container;


    public function __construct(RouterInterface $router, Container $container, SessionInterface $session)
    {
        $this->session = $session;
        $this->container = $container;
        $this->router = $router;

    }

    public function index()
    {
        $em = $this->container->get('doctrine')->getManager();
        return (count($em->getRepository(TgAppelProj::class)->findAll()) == 0) ? false : true;

    }
}