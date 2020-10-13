<?php

namespace App\Repository;

use App\Entity\TtGestionFormulairePhase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * @method TtGestionFormulairePhase|null find($id, $lockMode = null, $lockVersion = null)
 * @method TtGestionFormulairePhase|null findOneBy(array $criteria, array $orderBy = null)
 * @method TtGestionFormulairePhase[]    findAll()
 * @method TtGestionFormulairePhase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TtGestionFormulairePhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TtGestionFormulairePhase::class);
    }

    // /**
    //  * @return TtGestionFormulairePhase[] Returns an array of TtGestionFormulairePhase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findPermissions($idAppel, $idPhase, $idFormulaire, $field)
    {
        $qb = $this->createQueryBuilder('tgfp');
        $qb
            ->andWhere('tgfp.idPhase = :phase')
            ->andWhere('tgfp.field = :field')
        ;
        if ($idAppel) {
            $qb = $qb->andWhere('tgfp.idAppel in (:appel)');
            $qb = $qb->setParameter('appel', $idAppel);
        }
        if ($idFormulaire) {
            $qb = $qb->andWhere('tgfp.idFormulaire not in (:formulaire)');
            $qb = $qb->setParameter('formulaire', $idFormulaire);
        }

        $qb = $qb->setParameter('phase', $idPhase);
        $qb = $qb->setParameter('field', $field);

        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }

}
