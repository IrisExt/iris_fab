<?php

namespace App\Service;

use App\Entity\TgHabilitation;
use App\Security\AccessDeniedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use App\Controller\WidgetController as Widgets;

class HabilitationService
{
    private $security;
    protected $em;
    /**
     * @var Widgets
     */
    private $widgetController;

    /**
     * Habilitation constructor.
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param Widgets $widgetController
     */
    public function __construct(EntityManagerInterface $em, Security $security, Widgets $widgetController)
    {
        $this->em = $em;
        $this->security = $security;
        $this->widgetController = $widgetController;
    }

    public function habilitationUserWidget() : array
    {
        $this->access();
        $wid = [];
        $habs = $this->em->getRepository(TgHabilitation::class)->findBy([
                'idPersonne'=> $this->security->getUser()->getIdPersonne(),
                'blSupprime' => 1]
        );
        if($habs){
            foreach ($habs as $hab ){
                switch ($hab->getIdProfil()->getIdProfil()){
                    case 1 : //pilote
                        $wid[] = 'App\\Controller\\WidgetController::widgetPilote';
                        $wid[]= 'App\\Controller\\WidgetController:widgetAppel';continue  2; /* continue  2: Termine le switch et le foreach. */
                    case 10 : // DOSEM
                        $wid[]= 'App\\Controller\\WidgetController::widgetDosEm'; continue  2;

                    case 6 : // cps principal
                        $wid[] = 'App\\Controller\\WidgetController::widgetCpsP' ;
                        $wid[] = 'App\\Controller\\WidgetController::widgetAffectationRLComite';

                        continue 2;
                    case 7 : // cps Secondaire
                        $wid[] = 'App\\Controller\\WidgetController::widgetCpsS' ;
                        $wid[] = 'App\\Controller\\WidgetController::widgetAffectationRLComite'; continue 2;
                    case 12 : // Resp scientifique
                        $wid[] = 'App\\Controller\\WidgetController::widgetformSoumissionRespSc'; continue 2;
                    case 4 : //président
                        $wid[]= 'App\\Controller\\WidgetController::widgetPA';
                        $wid[] = 'App\\Controller\\WidgetController::widgetAffectationRLComite'; continue 2;

                    case 8 : // vise président
                        $wid[]= 'App\\Controller\\WidgetController::widgetPA';
                        $wid[] = 'App\\Controller\\WidgetController::widgetAffectationRLComite';continue  2;

                    case 15 : // porteur projet
                        $wid[]= 'App\\Controller\\WidgetController::widgetPorteurP'; continue  2;
                    case 16 : // Expert

                    continue  2;
                    case 9 : // membres
                        $wid[]= 'App\\Controller\\WidgetController::widgetAffectationRLMembre'; continue  2;
                    case 18 : // Resp administratif
                        $wid[] = 'App\\Controller\\WidgetController::widgetformSoumissionRespAdm'; continue 2;
                    case 17 : // Lecteurs

                     continue  2;
                    case 19 : // Rapporteur

                     continue  2;
                }

            }
        }
        return $wid;

    }

    public function access()
    {
        $user = $this->security->getUser() ; // le user

        if(empty($user) || null == $user->getIdPersonne()){ // verifier s'il est rattaché à une personne sinon accès impossible
            throw new AccessDeniedException();
        }
        return true;

    }


}