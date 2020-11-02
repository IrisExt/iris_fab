<?php

namespace App\Manager;

use App\Entity\TgComite;
use App\Repository\TgProjetRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjetManager
{
    private $tgProjetRepository;
    private $em;

    public function __construct (EntityManagerInterface $em, TgProjetRepository $tgProjetRepository)
    {
        $this->em = $em;
        $this->tgProjetRepository = $tgProjetRepository;
    }

    public function getComiteProjets(TgComite $tgComite)
    {
        return $this->tgProjetRepository->findBy(['idComite' => $tgComite]);
    }

    public function getProjectExperts ($idProjet)
    {
        return $this->tgProjetRepository->findBy(['idComite' => $tgComite]);
    }

    public function getProject ($idProjet)
    {
        return $this->tgProjetRepository->findOneBy(['idProjet' => $idProjet]);
    }
}
