<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;


class NiveauPhaseRepository extends EntityRepository
{

    public function phaseByAppel($appel)
    {
        $db = $this
            ->createQueryBuilder('n')
            ->select('count(n.idPhase) nb' , '(n.idPhase) idPhase' )
            ->join('n.idAppel', 'a')
            ->where('a.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->groupBy('n.idPhase')
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function phaseWithAppel($idAppel)
    {
        $query = $this->createQueryBuilder('p')
            ->select( 'ph.idPhase','phRef.lbNom')
            ->innerJoin('p.idPhase', 'ph')
            ->innerJoin('ph.idPhaseRef' , 'phRef')
            ->where('p.idAppel = :appel')
            ->setParameter('appel', $idAppel)
            ->groupby('ph.idPhase')
            ->addgroupby('phRef.lbNom')
            ->getQuery();

        return $query->getResult();
    }

}