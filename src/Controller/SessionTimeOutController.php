<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SessionTimeOutController
 * @package App\Controller
 *
 */
class SessionTimeOutController extends  BaseController
{
    /**
     * @var Session
     */
    private $securityToken;

    public function __construct(TokenStorageInterface $securityToken)
    {

        $this->securityToken = $securityToken;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/time_out", name="time_out")
     */
    public function redirectModalTimeOut(){

        if($this->securityToken->getToken()->getUser() != 'anon.'){
            return $this->redirectToRoute('accueil');
        };
//        if($this->securityToken->getToken())
        return $this->render('time_out.html.twig');
    }


}