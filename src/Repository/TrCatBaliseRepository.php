<?php

namespace App\Repository;

use App\Entity\TrCatBalise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrCatBalise|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrCatBalise|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrCatBalise[]    findAll()
 * @method TrCatBalise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrCatBaliseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrCatBalise::class);
    }

    // /**
    //  * @return TrCatBalise[] Returns an array of TrCatBalise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrCatBalise
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
