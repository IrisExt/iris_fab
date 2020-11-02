<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgComite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TgComiteRepository
 *
 * @package App\Repository
 */
class TgComiteRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TgFormulaireRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgComite::class);
        $this->entityManager = $entityManager;
    }

    public function updateDateComite($idComite, $newDateComite)
    {
        $updateDateEcheance = $this->entityManager->getRepository(TgComite::class)->find($idComite);
        if (!$updateDateEcheance) {
            return false;
        }
        $dateComite = explode('/', $newDateComite);
        $updateDateEcheance->setDhRenduEvalRl(new \DateTime($dateComite[2] . '-' . $dateComite[1] . '-' . $dateComite[0]));
        $this->entityManager->persist($updateDateEcheance);
        $this->entityManager->flush();
        return true;
    }
}
