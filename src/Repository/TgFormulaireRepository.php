<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgFormulaire;
use App\Entity\TgProjet;
use App\Entity\TlBlocForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * Class TgFormulaireRepository
 *
 * @package App\Repository
 */
class TgFormulaireRepository extends ServiceEntityRepository
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
        parent::__construct($registry, TgFormulaire::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $idFormulaire
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findIdProjetWithIdFormulaire(int $idFormulaire)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tgp.idProjet')
            ->from(TgProjet::class, 'tgp')
            ->leftJoin(
                TgComite::class,
                'tgc',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tgp.idComite = tgc.idComite'
            )
            ->leftJoin(
                TgAppelProj::class,
                'tgap',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tgc.idAppel = tgap.idAppel'
            )
            ->where('tgap.idFormulaire = :id_formulaire')
            ->setParameter('id_formulaire', $idFormulaire);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }

    /**
     * @return mixed
     */
    public function findFormulaires()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tgf.lbFormulaire,tgf.idFormulaire, tgap.idAppel')
            ->from(TgFormulaire::class, 'tgf')
            ->leftJoin(
                tl_formulaire_appel,
                'tgap',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'tgf.idFormulaire = tgap.idFormulaire'
            );
        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /**
     * @param int $idComite
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findIdProjetWithIdComite(int $idComite)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tgp.idProjet')
            ->from(TgProjet::class, 'tgp')
            ->where('tgp.idComite = :idComite')
            ->setParameter('idComite', $idComite);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function updateAddFormulaire($idClasseFormulaire , $updateForm = null){
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('f')
            ->from('App:TgFormulaire', 'f');
        if($updateForm != null){
            $qb->andWhere('f.idFormulaire != :updateform')
                ->setParameter('updateform', $updateForm);
        }
        $qb->andWhere('f.idClasseFormulaire = :classForm')
            ->setParameter('classForm', $idClasseFormulaire);
         $query = $qb->getQuery();
         $result = $query->getResult();
        return $result;
    }
}
