<?php

namespace App\Repository;

use App\Entity\FtCommandeApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * Class FtCommandeAppRepository
 * @method TgAppelProj|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgAppelProj|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgAppelProj[]    findAll()
 * @method TgAppelProj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class FtCommandeAppRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FtCommandeApp::class);
    }

    public function findRecentByComments($value): ?array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.idProjet = :val')
            ->setParameter('val', $value)
            ->orderBy('f.dhCommande', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }
}