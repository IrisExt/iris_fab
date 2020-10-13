<?php

namespace App\Repository;

use App\Entity\TgMcErc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TgMcErc|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgMcErc|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgMcErc[]    findAll()
 * @method TgMcErc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgMcErcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgMcErc::class);
    }
}