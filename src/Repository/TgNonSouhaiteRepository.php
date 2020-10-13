<?php

namespace App\Repository;

use App\Entity\TgNonSouhaite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TgNonSouhaite|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgNonSouhaite|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgNonSouhaite[]    findAll()
 * @method TgNonSouhaite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgNonSouhaiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgNonSouhaite::class);
    }

    /**
     * @param $idProjet
     * @return QueryBuilder
     */
    public function findbyProjet($idProjet)
    {
        $db = $this->createQueryBuilder('c');
        $result = $db->innerJoin('c.idProjet', 'm')
            ->where('m.idProjet = :idProjet')
            ->setParameter(':idProjet', $idProjet)
            ->getQuery()
            ->getResult();
        return $result;

    }
}