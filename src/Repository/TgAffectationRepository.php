<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAffectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class TgAffectationRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TrTypeDocRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgAffectation::class);

        $this->entityManager = $entityManager;
    }


    /**
     * @param $comite
     * @return mixed
     */
    public function affectationRL($comite){

        $db = $this->createQueryBuilder('a')
            ->leftJoin('a.idProjet', 'p')
            ->where('p.idComite = :comite')
            ->setParameter('comite', $comite)
            ->getQuery();

            return $db->getResult();
    }

    public function affectationExpComite($proj ,array $sollic){

        $db = $this->createQueryBuilder('a')
            ->leftJoin('a.idProjet', 'p')
            ->where('p.idProjet = :proj')
            ->andWhere('a.cdSollicitation in (:sollic)')
            ->setParameter('proj', $proj)
            ->setParameter('sollic', $sollic)
            ->getQuery();
        return $db->getResult();
    }


}
