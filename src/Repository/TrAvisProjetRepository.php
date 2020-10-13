<?php

namespace App\Repository;

use App\Entity\TrAvisProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TrAvisProjetRepository
 * @package App\Repository
 *
 * @method TrAvisProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrAvisProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrAvisProjet[]    findAll()
 * @method TrAvisProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrAvisProjetRepository extends ServiceEntityRepository
{
    /**
     * TrAvisProjetRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrAvisProjet::class);
    }



}
