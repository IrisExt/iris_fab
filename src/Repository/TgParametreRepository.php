<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgParametre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TgParametreRepository
 *
 * @package App\Repository
 */
class TgParametreRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TgParametreRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgParametre::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param $idAppel
     * @param $type
     * @param $taille
     * @param $nbrPages
     *
     * @return mixed
     */
    public function findParamsDocSc($idAppel, $type, $taille, $nbrPages){
        $db = $this
            ->createQueryBuilder('p')
            ->where('p.idAppel = :id_appel')
            ->andWhere('p.lbCode = :type')
            ->orWhere('p.lbCode = :taille')
            ->orWhere('p.lbCode = :nbPages')
            ->setParameter('id_appel', $idAppel)
            ->setParameter('type', $type)
            ->setParameter('taille', $taille)
            ->setParameter('nbPages', $nbrPages)
            ->getQuery()
            ->getResult();
        return $db;
    }
}
