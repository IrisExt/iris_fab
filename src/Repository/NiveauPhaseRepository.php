<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgNiveauPhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


class NiveauPhaseRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * NiveauPhaseRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, TgNiveauPhase::class);
        $this->entityManager = $entityManager;
    }


    public function phaseByAppel($appel)
    {
        $db = $this
            ->createQueryBuilder('n')
            ->select('count(n.idPhase) nb', '(n.idPhase) idPhase')
            ->join('n.idAppel', 'a')
            ->where('a.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->groupBy('n.idPhase')
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function phaseWithAppel($idAppel)
    {
        $query = $this->createQueryBuilder('p')
            ->select('ph.idPhase', 'phRef.lbNom')
            ->innerJoin('p.idPhase', 'ph')
            ->innerJoin('ph.idPhaseRef', 'phRef')
            ->where('p.idAppel = :appel')
            ->setParameter('appel', $idAppel)
            ->groupby('ph.idPhase')
            ->addgroupby('phRef.lbNom')
            ->getQuery();

        return $query->getResult();
    }
}
