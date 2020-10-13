<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;


class ComiteRepository extends EntityRepository
{

    public function findLstcomite($aapg)
    {
        $db = $this
            ->createQueryBuilder('a')
            ->join('a.idHabilitation', 'p')
            ->where('a.idAppel = :appel')
            ->andwhere('a.blActif = 1')
            ->andWhere('p.blSupprime = :supprime')
            ->setParameter('appel', $aapg)
            ->setParameter('supprime', 1)
            ->orderBy('a.lbAcr', 'ASC')
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function findLstcomiteDep($aapg, $dep)
    {
        $db = $this
            ->createQueryBuilder('a')
            ->Join('a.idDepartement', 'e')
            ->join('a.idHabilitation', 'p')
            ->where('a.idAppel = :appel')
            ->andwhere('a.blActif = 1')
            ->andWhere('p.blSupprime = 1')
            ->andwhere('e.lbLong = :dep')
            ->setParameter('appel', $aapg)
            ->setParameter('dep', $dep)
            ->orderBy('a.lbAcr', 'ASC')
            ->getQuery()
            ->getResult();

        return $db;
    }


    public function findParticipant($id)
    {
        $db = $this
            ->createQueryBuilder('c')
            ->InnerJoin('App:TgHabilitation', 'p', Join::WITH, 'c.idComite = p.idComite')
            ->where('c.idComite = :idcomite')
            ->setParameter('idcomite', $id)
            ->addSelect('p')
            ->getQuery()
            ->getResult();
        return $db;


    }




}