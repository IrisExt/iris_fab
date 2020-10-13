<?php


namespace App\Repository;

namespace App\Repository;

use App\Entity\TgCompetenceLangue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class TgCompetenceLangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgCompetenceLangue::class);
    }

    public function findLangueUser($personne){

        $qb = $this->createQueryBuilder('c')
//            ->addSelect('(c.idLangue)')
            ->where('c.idPersonne = :personne')
            ->setParameter('personne' , $personne)
            ->getQuery()
            ->getResult();
        return $qb;

    }

    public function test(){
        return true;
    }

}