<?php

namespace App\Repository;

use App\Entity\TrCategorieErc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method TrCategorieErc|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrCategorieErc|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrCategorieErc[]    findAll()
 * @method TrCategorieErc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrCategorieErc::class);
    }

    public function retrieveHydratedMcErc($idAppel)
    {
        if($idAppel) {
            $qb = $this->createQueryBuilder('c')
                ->select('c, d, m')
                ->leftJoin('c.TrDiscErcs', 'd')
                ->leftJoin( 'd.TgMcErcs','m')
                ->leftJoin( 'm.tlMcErcAppel','t')
                ->where('t.idAppel = :id')
                ->setParameter('id', $idAppel)
            ;
            return $qb->getQuery()->getResult();
        }
         return null;
    }

//    public function retrieveHydratedMotCleErc()
//    {
//        $qb = $this->createQueryBuilder('c')
//            ->select('c, m, d, e')
//            ->leftJoin('c.TrDiscErcs', 'd')
//            ->leftJoin( 'd.TgMcErcs','m')
//            ->leftJoin('m.tlPersonneMcErc', 'e')
//            ->orderby('e.ordre','ASC')
//        ;
//        return $qb->getQuery()->getResult();
//    }

    public function personneMcErc($personne){
        $qb = $this->createQueryBuilder('c')
            ->select('m.idMcErc')
            ->InnerJoin('c.TrDiscErcs', 'd')
            ->InnerJoin( 'd.TgMcErcs','m')
            ->InnerJoin('m.tlPersonneMcErc', 'e')
            ->where('e.idPersonne = :personne' )
            ->setParameter('personne', $personne)
            ->orderBy('e.ordre', 'ASC');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function motcleErcAffiche($personne){
        $qb = $this->createQueryBuilder('c')
            ->select('c, m, d')
            ->InnerJoin('c.TrDiscErcs', 'd')
            ->InnerJoin( 'd.TgMcErcs','m');
			if(!empty($this->personneMcErc($personne))){
          $qb ->where('m.idMcErc not in (:personneMcErc)')
              ->setParameter('personneMcErc' , $this->personneMcErc($personne))
			;
			}
        return $qb->getQuery()->getResult();
    }

    public function McErcPersonnes($personne){
        $qb = $this->createQueryBuilder('c')
            ->select('c, m, d')
            ->InnerJoin('c.TrDiscErcs', 'd')
            ->InnerJoin( 'd.TgMcErcs','m')
            ->InnerJoin('m.tlPersonneMcErc', 'e')
            ->where('e.idPersonne = :personne' )
            ->setParameter('personne', $personne)
            ->orderBy('e.ordre', 'ASC');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }
}