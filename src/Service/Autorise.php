<?php

namespace App\Service;



use App\Security\AccessDeniedHandler;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class Autorise
{
    private $security;
    protected $em;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function accesPersonne($profilAcces = [], $appel = null , $phase = null, $comite = null)
    {

        $user = $this->security->getUser(); // le user

        if(null == $user->getIdPersonne()){ // verifier s'il est rattaché à une personne sinon accès impossible

            throw new AccessDeniedException();

        }
        $profil = $_SESSION['profil'] = $_SESSION['_sf2_attributes']['profil'];

        // si le profil est dans le array profil autorisé "$profilAcces"  return true sinon accès impossible
        if(!in_array($profil,$profilAcces) ){

            throw new AccessDeniedException();

        }
        return true;
    }

}