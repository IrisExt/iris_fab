<?php


namespace App\Repository;




use App\Entity\TgPersCps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;



class PersCpsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgPersCps::class);
    }

    public function oragnismePrsCps(){
        return $this->createQueryBuilder('p')
            ->select('p.lbOrganisme', 'count(p)')
            ->where ('p.lbOrganisme is not null')
            ->groupBy('p.lbOrganisme')
            ->getQuery()
            ->getResult();
    }
}