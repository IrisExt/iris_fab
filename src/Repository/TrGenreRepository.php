<?php

namespace App\Repository;

use App\Entity\TrGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TrGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrGenre[]    findAll()
 * @method TrGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrGenre::class);
    }
}