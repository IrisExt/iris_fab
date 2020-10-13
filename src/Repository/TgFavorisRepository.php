<?php

namespace App\Repository;

use App\Entity\TgFavoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TgFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgFavoris[]    findAll()
 * @method TgFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgFavoris::class);
    }

}