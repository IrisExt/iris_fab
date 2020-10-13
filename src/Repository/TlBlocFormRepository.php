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

use App\Entity\TgBloc;
use App\Entity\TlBlocForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * Class TlBlocFormRepository
 * @package App\Repository
 */
class TlBlocFormRepository extends ServiceEntityRepository
{
    /**
     * TlBlocFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TlBlocForm::class);
    }

    /**
     * @param int $id_formulaire
     * @param string $order
     * @return mixed
     */
    public function findAllBlocsByFormulaireId(
        int $id_formulaire,
        string $order = 'asc'
    ) {

//        $qb = $this->entityManager->createQueryBuilder();
//        $qb
//            ->select('tl_bloc_form.rang', 'tgb')
//            ->from('App\Entity\TlBlocForm', 'tl_bloc_form')
//            ->leftJoin(
//                'App\Entity\TgBloc',
//                'tgb',
//                \Doctrine\ORM\Query\Expr\Join::WITH,
//                'tl_bloc_form.idBloc = tgb.idBloc'
//            )
//            ->where('tl_bloc_form.idFormulaire = :id_formulaire')
//            ->setParameter('id_formulaire', $id_formulaire)
//            ->orderBy('tl_bloc_form.rang', $order)
//            ->getQuery();


        $qb = $this->createQueryBuilder('tl_bloc_form')
            ->where('tl_bloc_form.idFormulaire = :id_formulaire')
            ->setParameter('id_formulaire', $id_formulaire)
            ->orderBy('tl_bloc_form.ordre', $order)
            ->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }
    /**
     * @param $formulaire
     * @return mixed
     */
    public function findBlocWithFormulaire($formulaire){

        $qb = $this->createQueryBuilder('f')
            ->select('(f.idBloc)')
            ->where('f.idFormulaire = :idform')
            ->setParameter('idform', $formulaire)
            ->getQuery();
        return $qb->getArrayResult();
    }
}
