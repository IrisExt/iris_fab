<?php

namespace App\Repository;

use App\Entity\TlAvisProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TrAvisProjetRepository
 * @package App\Repository
 *
 * @method TlAvisProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TlAvisProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TlAvisProjet[]    findAll()
 * @method TlAvisProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TlAvisProjetRepository extends ServiceEntityRepository
{
    /**
     * TlAvisProjetRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TlAvisProjet::class);
    }



}