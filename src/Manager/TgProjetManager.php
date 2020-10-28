<?php

namespace App\Manager;


use App\Repository\TgProjetRepository;

class TgProjetManager
{
    /**
     * @var TgRepository
     */
    private $tgProjetRepository;

    public function __construct(TgProjetRepository $tgProjetRepository)
    {
        $this->tgProjetRepository = $tgProjetRepository;
    }

    /**
     * Retourne le portefeuille de projets d'une Personne pour un Comité
     *
     * @param integer $idPersonne
     * @param integer $idComite
     * @return TgProjet[]
     */
    public function getPortefeuilleRL(int $idPersonne, int $idComite)
    {
        $roles = [1,2];
        return $this->tgProjetRepository->getPortefeuille($idPersonne, $idComite, $roles);
    }

}
