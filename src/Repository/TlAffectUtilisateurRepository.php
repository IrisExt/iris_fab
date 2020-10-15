<?php

namespace App\Repository;

use App\Entity\TlAffectUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TlAffectUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method TlAffectUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method TlAffectUtilisateur[]    findAll()
 * @method TlAffectUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TlAffectUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TlAffectUtilisateur::class);
    }

    // /**
    //  * @return TlAffectUtilisateur[] Returns an array of TlAffectUtilisateur objects
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
    public function findOneBySomeField($value): ?TlAffectUtilisateur
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
