<?php

namespace App\Manager;

use App\Entity\FtCommandeApp;
use App\Entity\TgProjet;
use App\Entity\TgPersonne;
use App\Repository\FtCommandeAppRepository;
use Doctrine\ORM\EntityManagerInterface;

class FtCommandeAppManager
{
    private $ftCommandeAppRepository;
    private $em;

    public function __construct (EntityManagerInterface $em, FtCommandeAppRepository $ftCommandeAppRepository)
    {
        $this->em = $em;
        $this->ftCommandeAppRepository = $ftCommandeAppRepository;
    }

    public function getPersonneComments ($idProjet, $type = ''): array
    {
        $personneComments = $this->ftCommandeAppRepository->findRecentByComments($idProjet);
        if ($type == '') {
            return $personneComments;
        } else {
            return $this->getPersonneCommentsAjax($personneComments);
        }
    }

    private function getPersonneCommentsAjax (array $personneComments): array
    {
        $comments = [];
        foreach ($personneComments as $value) {
            $comments[] = [
                "lbNomUsage" => $value->getIdPersonne()->getLbNomUsage(),
                "lbPrenom" => $value->getIdPersonne()->getLbPrenom(),
                "dhCommandeDay" => date_format($value->getDhCommande(),"d/m/Y"),
                "dhCommandeTime" => date_format($value->getDhCommande(),"H:i"),
                "txtCommentaire" => $value->getTxtCommentaire()
            ];
        }

        return $comments;
    }

    public function setPersonneComment (TgPersonne $idPersonne, TgProjet $idProjet, string $comment)
    {
        $ftCommandeApp = new FtCommandeApp();

        $ftCommandeApp->setIdProjet($idProjet);
        $ftCommandeApp->setIdPersonne($idPersonne);
        $ftCommandeApp->setTxtCommentaire($comment);

        $this->em->persist($ftCommandeApp);
        $this->em->flush();

        return true;
    }
}

