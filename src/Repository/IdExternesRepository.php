<?php


namespace App\Repository;


use App\Entity\TgIdExternes;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class IdExternesRepository
 * @package App\Repository
 */
class IdExternesRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgIdExternes::class);
    }

}