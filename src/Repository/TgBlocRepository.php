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
use App\Repository\TlBlocFormRepository;



/**
 * Class TgBlocRepository
 * @package App\Repository
 */
class TgBlocRepository extends ServiceEntityRepository
{
    /**
     * @var TlBlocFormRepository
     */
    private $tlBlocFormRepository;

    public function __construct(ManagerRegistry $registry, TlBlocFormRepository $tlBlocFormRepository)
    {
        $this->tlBlocFormRepository = $tlBlocFormRepository;
        parent::__construct($registry, TgBloc::class);
    }


    public function findBlocNotwithForm($idFormulaire){

        $qb = $this->createQueryBuilder('b')
            ->where('b.idBloc not in (:blocform)')
            ->setParameter('blocform' , $this->tlBlocFormRepository->findBlocWithFormulaire($idFormulaire))
            ->getQuery();
        return $qb->getResult();
    }
}
