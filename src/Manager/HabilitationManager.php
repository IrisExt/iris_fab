<?php


namespace App\Manager;


use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgPersonne;
use App\Entity\TgPhase;
use App\Entity\TgProjet;
use App\Entity\TrProfil;
use App\Repository\HabilitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Security;

class HabilitationManager
{

    /**
     * @var HabilitationRepository
     */
    private $habilitationRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserManagerInterface
     */
    private $user;
    /**
     * @var Security
     */
    private $security;

    /**
     * HabilitaionManager constructor.
     * @param EntityManagerInterface $em
     * @param HabilitationRepository $habilitationRepository
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, HabilitationRepository $habilitationRepository, Security $security)
    {

        $this->habilitationRepository = $habilitationRepository;
        $this->em = $em;

        $this->security = $security;
    }

    public function getHabProfilPersonne(TgPersonne $tgPersonne, trProfil $trProfil) {
        return $this->habilitationRepository->findOneBy(['idPersonne' => $tgPersonne, 'idProfil' => $trProfil]);

    }

    public function getHabComitePersonne(TgComite $tgComite){
        return $this->habilitationRepository->findBy(['idComite'=> $tgComite]);
    }

    public function getHabPhasePersonne(TgComite $tgComite){
        return $this->habilitationRepository->findBy(['idComite'=> $tgComite]);
    }

    public function setHabilitation(TgPersonne $tgPersonne, TrProfil $trProfil, ?TgComite $tgComite, ?TgAppelProj $tgAppelProj, ?TgPhase $tgPhase, ?TgProjet $tgProjet)
    {
        $userConnect = $this->security->getUser()->getIdPersonne();
        $hab = new TgHabilitation();
        $hab
            ->setBlSupprime(1)
            ->setIdPersonne($tgPersonne)
            ->setIdProfil($trProfil)
            ->setLbRespMaj($userConnect->getLbNomUsage().' '.$userConnect->getLbPrenom());
        $tgComite    ? $hab->addIdComite($tgComite):null;
        $tgAppelProj ? $hab->addIdAppel($tgAppelProj):null;
        $tgPhase     ? $hab->addIdPhase($tgPhase):null;
        $tgProjet    ? $hab->addIdProjet($tgProjet):null;
        $this->em->persist($hab);
        $this->em->flush();

        return true;
    }
}