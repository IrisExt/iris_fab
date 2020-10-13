<?php

namespace App\Manager;

use App\Repository\HabilitationRepository;

class ConstitutionCesManager {
    /**
     * @var HabilitationRepository
     */
    private $habilitationRepository;


    /**
     * ConstitutionCesManager constructor.
     * @param HabilitationRepository $habilitationRepository
     */
    public function __construct(HabilitationRepository $habilitationRepository)
    {
        $this->habilitationRepository = $habilitationRepository;
    }


}