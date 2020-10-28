<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;
use App\Entity\TgFormulaire;
use App\Entity\TgProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

/**
 * Class TgProjetRepository
 *
 * @package App\Repository
 */
class TgProjetRepository extends ServiceEntityRepository
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
        parent::__construct($registry, TgProjet::class);
        $this->entityManager = $entityManager;
    }


    /**
     * @param $comite
     * @return array|int|string
     */
    public function findComiteWithProjet($comite){

        $qb = $this->createQueryBuilder('p')
            ->where('p.idComite = :idComite')
            ->setParameter('idComite', $comite)
            ->getQuery();
        return $qb->getArrayResult();
    }

    /**
     * @param $porteur
     * @param $niveau
     * @param $phaseRef
     * @return int|mixed|string
     */
    public function AppelWithSoumPersonne($porteur,$niveau){
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idAppel','a')
            ->join('a.niveauEnCours', 'n')
            ->join('n.idPhase', 'ph')
            ->join('ph.idPhaseRef', 'trp')
            ->join('n.idTypeNiveu', 'trn')
            ->andWhere('p.porteur = :porteur')
            ->andWhere('trn.idTypeNiveu = :niveau')
            ->setParameter('porteur', $porteur)
            ->setParameter('niveau', $niveau)
            ->getQuery()->getResult();

        return $db;
    }



    public function getAcros($idProjet, $idAppel)
    {
        $qb = $this->createQueryBuilder('tg_projet');
        $qb

            ->where('tg_projet.idProjet != :projet')
            ->andWhere('tg_projet.lbAcro is not null')
            ->andWhere('tg_projet.idAppel = :appel')
            ->setParameter('projet', $idProjet)
            ->setParameter('appel', $idAppel);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Retourne le portefeuille de projet d'une personne pour un comité selon les rôles indiqués
     *
     * @param integer $idPersonne
     * @param integer $idComite
     * @param array $roles
     * @return TgProjet[]
     */
    public function getPortefeuille(int $idPersonne, int $idComite, array $roles = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->join('p.tgAffectations', 'a')
            ->where('p.idComite = :idComite')
            ->andWhere('h.idPersonne = :idPersonne')
            ->andWhere('a.idPersonne = :idPersonne')
            ->andWhere('a.idStAffect in (:roles)')
            ->setParameter('idComite', $idComite)
            ->setParameter('idPersonne', $idPersonne)
            ->setParameter('roles', $roles)
            ->getQuery();
        return $qb->getResult();
    }

}
