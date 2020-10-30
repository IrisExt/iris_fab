<?php

namespace App\Manager;

use App\Repository\NiveauPhaseRepository;

class NiveauPhaseManager
{
    private $niveauPhaseRepository;

    public function __construct(NiveauPhaseRepository $niveauPhaseRepository)
    {
        $this->niveauPhaseRepository = $niveauPhaseRepository;
    }
}
