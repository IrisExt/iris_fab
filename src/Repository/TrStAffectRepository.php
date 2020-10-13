<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrStAffect;
use App\Entity\TrTypeDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TrTypeDocRepository
 * @package App\Repository
 */
class TrStAffectRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TrTypeDocRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TrStAffect::class);

        $this->entityManager = $entityManager;
    }

    public function findAffectWithPhase(array $notAfectPhase)
    {

        $db =  $this->createQueryBuilder('c')
            ->where('c.symbole not in (:symbole)')
            ->setParameter('symbole',$notAfectPhase)
            ->getQuery()
            ->getResult()
        ;
        return $db;
    }

    public function findAffectRL(array $notAfect)
    {
        $db =  $this->createQueryBuilder('c')
            ->where('c.symbole not in (:symbole)')
            ->setParameter('symbole',$notAfect)
            ->getQuery()
            ->getResult()
        ;
        return $db;
    }

}