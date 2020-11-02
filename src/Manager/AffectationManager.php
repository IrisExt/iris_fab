<?php

namespace App\Manager;

use App\Entity\TgComite;
use App\Entity\TgAffectation;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Repository\TgAffectationRepository;
use App\Repository\TgProjetRepository;
use App\Repository\TgComiteRepository;
use App\Repository\FtCommandeAppRepository;
use Doctrine\ORM\EntityManagerInterface;

class AffectationManager
{

    private $tgAffectationRepository;
    private $tgProjetRepository;
    private $ftCommandeAppRepository;
    private $em;

    public function __construct(EntityManagerInterface $em, TgAffectationRepository $tgAffectationRepository, TgProjetRepository $tgProjetRepository, FtCommandeAppRepository $ftCommandeAppRepository, TgComiteRepository $tgComiteRepository)
    {
        $this->em = $em;
        $this->tgAffectationRepository = $tgAffectationRepository;
        $this->tgProjetRepository = $tgProjetRepository;
        $this->ftCommandeAppRepository = $ftCommandeAppRepository;
        $this->tgComiteRepository = $tgComiteRepository;
    }

    public function getAllAffections()
    {
        return $this->tgAffectationRepository->findAll();
    }

    public function getComiteProjets(TgComite $tgComite)
    {
        return $this->tgProjetRepository->findBy(['idComite' => $tgComite]);
    }

    // public function getProjectExperts($idProjet)
    // {
    //     return $this->tgProjetRepository->findBy(['idComite' => $tgComite]);
    // }

    public function getProject($idProjet)
    {
        return $this->tgProjetRepository->findOneBy(['idProjet' => $idProjet]);
    }

    public function setDateRendu($idPersonne, $idAffectation, $dhRendu)
    {
        $affectation = $this->tgAffectationRepository->findOneBy([
            'idPersonne' => $idPersonne,
            'idAffectation' => $idAffectation
        ]);
        if (!$affectation) {
            return false;
        }
        $dateRendu = explode('/', $dhRendu);
        $affectation->setDhRendu(new \DateTime($dateRendu[2] . '-' . $dateRendu[1] . '-' . $dateRendu[0]));

        $this->em->persist($affectation);
        $this->em->flush();

        return true;
    }

    public function setCriticiteToProject($projet)
    {
        return $this->tgAffectationRepository->setCriticiteToProject($projet);
    }

    /**
     * Retourne si une personne est en conflit avec un projet.
     *
     * @param TgProjet $projet
     * @param TgPersonne $personne
     * @return boolean
     */
    public function utilisateurEstEnConflit(TgProjet $projet, TgPersonne $personne): bool
    {
        $affectation = $this->tgAffectationRepository->findOneBy([
            'idPersonne' => $personne->getIdPersonne(),
            'idProjet' => $projet->getIdProjet(),
        ]);

        return isset($affectation) && $affectation->getCdStsEvaluation() === 'ENC';
    }
}
