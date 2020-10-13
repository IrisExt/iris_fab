<?php

namespace App\Repository;


use App\Entity\TgMotCleCv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


///**
// * @method TgMotCleCv|null find($id, $lockMode = null, $lockVersion = null)
// * @method TgMotCleCv|null findOneBy(array $criteria, array $orderBy = null)
// * @method TgMotCleCv[]    findAll()
// * @method TgMotCleCv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
class TgMotCleCvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgMotCleCv::class);
    }

    /**
     * @param $personne
     * @return mixed
     */
    public function findMaxOrdreMcLibrePers($personne)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m.ordre AS max_ordre');
        $qb->where('m.idPersonne = :personne')->setParameter('personne', $personne);
        $qb->setMaxResults(1);
        $qb->orderBy('max_ordre', 'DESC');

        return $qb->getQuery()->getOneOrNullResult();
    }
}