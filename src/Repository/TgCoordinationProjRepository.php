<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgCoordinationProj;
use App\Entity\TgFormulaire;
use App\Entity\TgProjet;
use App\Entity\TlBlocForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * Class TgCoordinationProjRepository
 * @package App\Repository
 */
class TgCoordinationProjRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TgFormulaireRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgCoordinationProj::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $idProjet
     * @param string $idPays
     * @return mixed
     */
    public function getCoordEtrByProjet(int $idProjet, string $idPays)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.idProjet = :projet')
            ->andWhere('c.cdPays != :pays')
            ->setParameter('pays', $idPays)
           ->setParameter('projet', $idProjet);

        return $qb->getQuery()
            ->getResult();
    }
}
