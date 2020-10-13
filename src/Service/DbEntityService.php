<?php

namespace App\Service;

use App\Entity\TgAdrMail;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class DbEntityService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function dbAdrMail(TgAdrMail $tgAdrMail, string $adreMail, $tgPersonne, bool $adrPref, bool $blNotif, bool $blValide)
    {
        $existMail = $this->EntityFindByOnParam(TgAdrMail::class, 'adrMail', $adreMail);
        if ($existMail) {
            $tgAdrMail = $existMail;
        }

        try {
            $tgAdrMail
                ->setAdrMail($adreMail)
                ->setIdPersonne($tgPersonne)
                ->setBlNotification($blNotif)
                ->setBlValide($blValide)
                ->setAdrPref($adrPref);
            $this->entityManager->persist($tgAdrMail);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            return $e;
        }

        return true;
    }

    public function EntityFindByOnParam($entity, $col, $value)
    {
        return
            $this->entityManager->getRepository($entity)->findOneBy([$col => $value]);
    }
}
