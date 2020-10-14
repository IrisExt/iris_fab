<?php

namespace App\Manager;

use App\Repository\TlStsEvaluationRepository;

class TlStsEvaluationManager
{
    private $tlStsEvaluationRepository;

    public function __construct(
        TlStsEvaluationRepository $tlStsEvaluationRepository
    ) {
        $this->tlStsEvaluationRepository = $tlStsEvaluationRepository;
    }

    public function getAllStsEvaluations()
    {
        return $this->tlStsEvaluationRepository->findAll();
    }
}
