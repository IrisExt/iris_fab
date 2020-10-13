<?php

namespace App\Repository;

use App\Entity\TgBalise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TgBalise|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgBalise|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgBalise[]    findAll()
 * @method TgBalise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgBaliseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgBalise::class);
    }

    // /**
    //  * @return TgBalise[] Returns an array of TgBalise objects
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
    public function findOneBySomeField($value): ?TgBalise
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
