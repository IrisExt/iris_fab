<?php

namespace App\Manager;

use App\Entity\TgComite;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TgPhase;
use App\Entity\TrEtatSol;
use App\Entity\TrPhase;
use App\Entity\TrProfil;
use App\Repository\HabilitationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConstitutionCesManager {

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var HabilitationManager
     */
    private $habilitationManager;

    /**
     * ConstitutionCesManager constructor.
     * @param EntityManagerInterface $em
     * @param HabilitationManager $habilitationManager
     */
    public function __construct(EntityManagerInterface $em, HabilitationManager $habilitationManager)
    {

        $this->em = $em;
        $this->habilitationManager = $habilitationManager;
    }

    public function setNewParticipComite(TgPersonne $tgPersonne, TrProfil $trProfil, TgComite $tgComite, TrPhase $trPhase, TrEtatSol $trEtatSol)
    {
        $participation = new TgParticipation();
        $participation->setIdPersonne($tgPersonne)
            ->setIdComite($tgComite)
            ->setIdPhaseRef($trPhase)
            ->setCdEtatSollicitation($trEtatSol)
            ->setBlSupprime(1)
            ->setIdProfil($trProfil);
        $this->em->persist($participation);

        return true;
    }


}