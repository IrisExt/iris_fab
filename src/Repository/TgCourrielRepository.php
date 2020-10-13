<?php

namespace App\Repository;

use App\Entity\TgCourriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TgCourrielRepository
 * @package App\Repository
 */
class TgCourrielRepository extends ServiceEntityRepository
{


    private $entityManager;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgCourriel::class);
        $this->entityManager = $entityManager;
    }


    // /**
    //  * @return TgCourriel[] Returns an array of TgCourriel objects
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


    public function findCourrielSearch(array $idCourriels, $catModel)
    {
        $db =  $this->createQueryBuilder('c')
            ->innerJoin('c.idCatModele', 'catM')
            ->andWhere('c.idCourriel in (:desi)')
            ->orWhere('catM.idCatModele = :idcat')
            ->setParameter('desi', $idCourriels)
            ->setParameter('idcat', $catModel)
            ->getQuery()
            ->getResult()
        ;
        return $db;
    }

}
