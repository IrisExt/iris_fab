<?php

namespace App\Repository;

use App\Entity\TgCompetenceLangue;
use App\Entity\TrLangue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * Class TrLangueRepository
 * @package App\Repository
 */
class TrLangueRepository extends ServiceEntityRepository
{
    /**
     * @var TgCompetenceLangeRepository
     */
    private $compLanRepo;

    /**
     * TrLangueRepository constructor.
     * @param ManagerRegistry $registry
     * @param TgCompetenceLangeRepository $compLanRepo
     */
    public function __construct(ManagerRegistry $registry, TgCompetenceLangeRepository $compLanRepo )
    {
        parent::__construct($registry, TrLangue::class);

        $this->compLanRepo = $compLanRepo;
    }

    public function findCompetenceLangueNotInUser($user)
    {

       $tgCompLangue =  $this->compLanRepo->findLangueUser($user);
        $tabLangue =[];
       foreach ($tgCompLangue as $idLangue){
           $tabLangue[] = $idLangue->getIdLangue();
       }
        $qb = $this->createQueryBuilder('l')
            ->where('l.idLangue not in (:compLangue)')
            ->setParameter('compLangue', $tabLangue)
            ->getQuery()
            ->getResult();
        return $qb;


    }


}


