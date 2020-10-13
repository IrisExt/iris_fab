<?php


namespace App\Manager;


use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgPersonne;
use App\Entity\TrProfil;
use App\Repository\HabilitationRepository;
use App\Repository\ProfilRepository;

class HabilitationManager
{

    /**
     * @var HabilitationRepository
     */
    private $habilitationRepository;
    /**
     * @var ProfilRepository
     */
    private $profilRepository;

    /**
     * HabilitaionManager constructor.
     * @param HabilitationRepository $habilitationRepository
     * @param ProfilRepository $profilRepository
     */
    public function __construct(HabilitationRepository $habilitationRepository, ProfilRepository $profilRepository)
    {

        $this->habilitationRepository = $habilitationRepository;
        $this->profilRepository = $profilRepository;
    }

    public function getHabProfilPersonne(TgPersonne $tgPersonne, trProfil $trProfil) {
        return $this->habilitationRepository->findOneBy(['idPersonne' => $tgPersonne, 'idProfil' => $trProfil]);

    }

    public function getHabComitePersonne(TgComite $tgComite){
        return $this->habilitationRepository->findBy(['idComite'=> $tgComite]);
    }
    public function getHabAppelPersonne(TgAppelProj $tgAppelProj){
        return $this->habilitationRepository->AppelPersonne();
    }
    public function getHabPhasePersonne(TgComite $tgComite){
        return $this->habilitationRepository->findBy(['idComite'=> $tgComite]);
    }
}