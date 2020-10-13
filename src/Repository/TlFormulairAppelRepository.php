<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\TgProjet;
use App\Entity\TlFormulaireAppel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TlBlocFormRepository
 * @package App\Repository
 */
class TlFormulairAppelRepository extends ServiceEntityRepository
{
    /**
     * TlBlocFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TlFormulaireAppel::class);
    }


    public function findAppelFormBlocSoumission($personne, $find){
        $qb = $this
            ->createQueryBuilder('f')
            ->join('f.idFormulaire', 'formu')
            ->InnerJoin('App:TgNiveauPhase', 'niv', Join::WITH, 'f.idAppel = niv.idAppel')
            ->InnerJoin('App:TgPhase', 'pha', Join::WITH, 'f.idPhase = pha.idPhase')
            ->andwhere(':now BETWEEN niv.dhDebut AND niv.dhFin')
            ->andWhere('pha.idPhaseRef = 1')
            ->andWhere('formu.idClasseFormulaire = 1');
        if($this->findProjetPersonneInAppel($personne) != null){
            if ($find == false) {
                $qb = $qb->andWhere('f.idAppel in (:appel)');
            }else {
                $qb = $qb->andWhere('f.idAppel not in (:appel)');
            };
//            $qb = $qb->setParameter('appel', $this->findProjetPersonneInAppel($personne));
            $qb = $qb->setParameter('appel', $this->findProjetPersonneInAppel($personne));
        }
        $qb = $qb
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('f.idAppel', 'ASC')
            ->getQuery()

            ->getResult();

        return $qb;

    }

    public function findFormBlocSoumission($personne, $find, $appel){
        $qb = $this
            ->createQueryBuilder('f')
            ->join('f.idFormulaire', 'formu')
            ->InnerJoin('App:TgNiveauPhase', 'niv', Join::WITH, 'f.idAppel = niv.idAppel')
            ->InnerJoin('App:TgPhase', 'pha', Join::WITH, 'f.idPhase = pha.idPhase')
            ->andwhere(':now BETWEEN niv.dhDebut AND niv.dhFin')
            ->andWhere('pha.idPhaseRef = 1')
            ->andWhere('formu.idClasseFormulaire = 1')
            ->andWhere('f.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('f.idAppel', 'ASC')
            ->getQuery()
            ->getResult();

        return $qb;

    }

    public function findProjetPersonneInAppel($personne){
        $appels=[];

        $projets =
            $this->getEntityManager()->getRepository(TgProjet::class)->findBy(['porteur' => $personne]);
        if(empty($projets)){
            return $appels = null;
        }else{
            foreach ($projets as $appel ){
                $appels[] = $appel->getIdAppel();
            }
        }

        return $appels;
    }


    public function AppelWithSoumission(array $appel){
        $qb = $this
            ->createQueryBuilder('f')
            ->join('f.idFormulaire', 'formu')
            ->InnerJoin('App:TgNiveauPhase', 'niv', Join::WITH, 'f.idAppel = niv.idAppel')
            ->InnerJoin('App:TgPhase', 'pha', Join::WITH, 'f.idPhase = pha.idPhase')
            ->andwhere(':now BETWEEN niv.dhDebut AND niv.dhFin')
            ->andWhere('pha.idPhaseRef = 1') // soumission
            ->andWhere('formu.idClasseFormulaire = 1') // formulaire type soumission
            ->andWhere('f.idAppel in (:appel)')
            ->setParameter('appel', $appel)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('f.idAppel', 'ASC')
            ->getQuery()
            ->getResult();

        return $qb;
    }
}
