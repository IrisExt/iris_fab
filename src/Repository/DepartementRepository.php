<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;


class DepartementRepository extends EntityRepository
{
    public function depComiteExiste($aapg)
    {
        return
            $this
                ->createQueryBuilder('a')
                ->LeftJoin('a.idComite', 'c')
                ->Where('c.idAppel = :appel')
                ->andwhere('c.blActif = 1')
                ->setParameter('appel' , $aapg)
                ->getQuery()
                ->getResult();
    }

}