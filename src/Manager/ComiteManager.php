<?php

namespace App\Manager;

use App\Repository\TgComiteRepository;

class ComiteManager
{

    private $tgComiteRepository;

    public function __construct(TgComiteRepository $tgComiteRepository)
    {
        $this->tgComiteRepository = $tgComiteRepository;
    }

    public function updateDateComite($idComite, $newDateComite)
    {
        return $this->tgComiteRepository->updateDateComite($idComite, $newDateComite);
    }
}
