<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgFormulaire;
use App\Entity\TgProjet;
use App\Entity\TrInstFi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * Class TrInstFiRepository
 * @package App\Repository
 */
class TrInstFiRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TrInstFiRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TrInstFi::class);
        $this->entityManager = $entityManager;
    }

}
