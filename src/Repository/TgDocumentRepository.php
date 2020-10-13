<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TgDocumentRepository
 * @package App\Repository
 */
class TgDocumentRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TgDocumentRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgDocument::class);

        $this->entityManager = $entityManager;
    }

}
