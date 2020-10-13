<?php

namespace App\Repository;

use App\Entity\TgPartenariat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TgPartenariat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgPartenariat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgPartenariat[]    findAll()
 * @method TgPartenariat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgPartenariatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgPartenariat::class);
    }

    /**
     * @param $idProjet
     * @return QueryBuilder
     */
    public function findbyProjet($idProjet)
    {
        $db = $this->createQueryBuilder('p');
        $result =
            $db
                //->innerJoin('p.idOrganisme', 'o')
                ->where('p.idProjet = :idProjet')
                ->setParameter(':idProjet', $idProjet)
                ->getQuery()
                ->getResult();

        return $result;

    }
}