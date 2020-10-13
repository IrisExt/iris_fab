<?php

namespace App\Repository;


use App\Entity\TgOrganisme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * @method TgOrganisme|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgOrganisme|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgOrganisme[]    findAll()
 * @method TgOrganisme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgOrganismeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgOrganisme::class);
    }

    /**
     * @param $personne
     * @return mixed
     */
    public function findOrganismePers($personne)
    {
        $qb = $this->createQueryBuilder('o')
//        ->innerJoin('o.idAdresse', 'a')
        ->Join('o.idPersonne', 'p')
        ->where('p.idPersonne = :personne')
        ->setParameter('personne', $personne)
        ->getQuery();
        return $qb->getResult();

    }
}