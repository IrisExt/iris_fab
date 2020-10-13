<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProfilRepository extends EntityRepository
{
    public function profilMmbreVp()
    {
        return
            $this
                ->createQueryBuilder('p')
                ->Where('p.idProfil in (:profil)')
                ->setParameter('profil' , [9,8])
                ->orderBy('p.lbProfil', 'ASC')
                ->getQuery()
                ->getResult();

    }

}