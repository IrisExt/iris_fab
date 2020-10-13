<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class SeanceRepository extends EntityRepository
{

    /**
     * @param $seance
     * @param $idreunion
     * @return mixed
     * verification des seances en double après modification
     */
    public function modifSeanceDoublon($seance,$idreunion,$comite)
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.idReunion' , 'r')
            ->where('s.idReunion = :idreunion')
            ->andWhere('s.idComite = :comite')
            ->andwhere('s.idSeance not in (:idseance)')
            ->andwhere('r.blActif = 1')
            ->setParameter('idreunion', $idreunion)
            ->setParameter('idseance', $seance)
            ->setParameter('comite', $comite)
            ->getQuery()
            ->getResult();
        return $qb;
    }

}