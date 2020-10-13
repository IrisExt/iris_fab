<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class ReunionRepository extends EntityRepository
{

    public function seanceByReunion($appel)
    {
        $db = $this
            ->createQueryBuilder('r')
            ->where('r.idAppel = :appel')
            ->andWhere('r.blActif = :blactif')
            ->setParameter('appel', $appel)
            ->setParameter('blactif', 1)
            ->getQuery()
            ->getResult();
        return $db;
    }

}