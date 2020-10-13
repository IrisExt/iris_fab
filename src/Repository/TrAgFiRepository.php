<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrAgFi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TrAgFiRepository
 * @package App\Repository
 */
class TrAgFiRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TrAgFiRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TrAgFi::class);

        $this->entityManager = $entityManager;
    }

}
