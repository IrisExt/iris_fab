<?php

namespace App\Repository;

use App\Entity\TgAdrMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TgAdrMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgAdrMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgAdrMail[]    findAll()
 * @method TgAdrMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgAdrMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgAdrMail::class);
    }
}