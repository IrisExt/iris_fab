<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class PhaseRepository extends EntityRepository
{
    public function findPhaseMmbre($appel, $refPhase)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->innerJoin('p.idPhaseRef' , 'r')
            ->innerJoin('r.idAppel', 'a')
            ->where('a.idAppel = :appel')
            ->andWhere('r.idPhaseRef = :refphase')
            ->setParameter('appel', $appel)
            ->setParameter('refphase', $refPhase)
            ->getQuery()
            ->getResult();
            return $db;
    }

    public function phaseForAppel($appel){
        $db = $this->createQueryBuilder('p')
            ->select('p.idPhase', 'phref.lbNom')
            ->innerJoin('p.idPhaseRef', 'phref')
            ->innerJoin('p.idNiveauPhase' , 'niv')
            ->innerJoin('niv.idAppel', 'app')
            ->where('niv.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->distinct()
            ->getQuery();
        return $db->getResult();
    }



}