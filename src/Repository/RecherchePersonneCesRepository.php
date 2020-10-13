<?php

namespace App\Repository;

use App\Entity\RecherchePersonneCes;
use App\Entity\TgPersonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class RecherchePersonneCesRepository extends EntityRepository
{
//    public function __construct(ManagerRegistry $registry, $entityClass)
//    {
//        parent::__construct($registry, $entityClass);
//    }

    public function findPersonneRecherche(RecherchePersonneCes $recherchePersonneCes) :query
    {
        $query = $this->findPersonneCes();

        return $query->getQuery();
    }

    public function findPersonneCes() :QueryBuilder
    {
        return $this->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\TgPersonne', 'p');

    }

}