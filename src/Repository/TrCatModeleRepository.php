<?php

namespace App\Repository;

use App\Entity\TrCatModele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrCatModele|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrCatModele|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrCatModele[]    findAll()
 * @method TrCatModele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrCatModeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrCatModele::class);
    }

    // /**
    //  * @return TrCatModele[] Returns an array of TrCatModele objects
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
    public function findOneBySomeField($value): ?TrCatModele
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
