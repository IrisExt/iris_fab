<?php


namespace App\Repository;


use App\Entity\TgAffectation;
use App\Entity\TgParticipation;
use App\Entity\TlAvisProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;


class ParticipationRepository extends ServiceEntityRepository
{


    private $entityManager;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgParticipation::class);
        $this->entityManager = $entityManager;
    }


    public function findAllPartComite()
    {
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->Join('h.idComite' , 'c')
            ->where('c.blActif = :actif' )
            ->andWhere('p.blSupprime = 1')
            ->setParameter('actif', 1)
            ->getQuery()
            ->getResult();

        return $db;
    }
    public function affectRlParticipation($idComite, $idProfil)
    {
        $db = $this
            ->createQueryBuilder('par')
            ->join('par.idPersonne', 'pers')
            ->where('par.idComite = :comite')
            ->andWhere('par.idProfil = :profil')
            ->setParameter('comite', $idComite)
            ->setParameter('profil', $idProfil)
            ->orderBy('pers.lbNomUsage', 'ASC')
            ->getQuery()
            ->getResult();

        return $db;
    }

    public function findPartiComite($role1)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->Join('h.idComite', 'b')
            ->join('h.idPersonne', 'c')
            ->join('h.idProfil', 'r')
            ->andWhere('r.idProfil in (:prof)' )
            ->andWhere('p.blSupprime = 1')
            ->setParameter('profil' , array($role1))
            ->orderBy('b.lbAcr', 'ASC')
            ->getQuery()
            ->getResult();

        return $db;
    }

    public function findPartParComite($idComite)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->Join('h.idComite', 'c')
            ->join('h.idPersonne', 'pers')
            ->where('c.blActif = :actif' )
            ->andWhere('c.idComite = :comite' )
            ->andWhere('p.blSupprime = :blsupprime') // bl != de 0
            ->setParameter('actif', 1)
            ->setParameter('blsupprime', 1)
            ->setParameter('comite' , $idComite)
            ->getQuery()
            ->getResult();

        return $db;
    }

    /**
     * reponse true ou false si la personne connecté (cps ou cosse ) est dans un comite
     */
    public function userParticipantComite($user)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->select('(h.idComite)')
            ->Join('h.idComite', 'c')
            ->join('h.idPersonne', 'pers')
            ->where('c.blActif = :actif' )
            ->andWhere('p.blSupprime = :blsupprime') // bl != de 0
            ->andWhere('h.idPersonne = :idpersonne')
            ->setParameter('actif', 1)
            ->setParameter('blsupprime', 1)
            ->setParameter('idpersonne', $user)
            ->groupBy('h.idComite')
            ->orderBy('h.idComite' , 'ASC')
            ->getQuery()
            ->getResult();

        return $db;
    }

    public function findPartParComiteCes($idComite, $phase, $aapg)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->Join('p.idHabilitation', 'h')
            ->join('h.idPersonne', 'pers')
            ->join('h.idComite', 'c')
            ->LeftJoin('pers.idPersCps', 'pc')
            ->where('h.idAppel = :appel')
            ->andwhere('c.blActif = :actif' )
            ->andWhere('h.idComite = :comite' )
            ->andWhere('h.idProfil not in (:profil)') // bl != de 0
            ->setParameter('appel', $aapg)
            ->setParameter('actif', 1)
            ->setParameter('profil', [6,7])
            ->setParameter('comite' , $idComite)
            ->orderBy('p.prioGrp', 'ASC')
            ->getQuery()
            ->getResult();

        return $db;
    }

    /**
     * @param $comite
     * @return mixed
     * ordre priorité constitution Ces
     */
    public function requetOrdreParti($comite){

        $db = $this
             ->createQueryBuilder('p')
//             ->Join('p.idHabilitation', 'h')
             ->where('p.idComite = :comite')
//            ->andWhere('h.idProfil not in (:profil)')
            ->setParameter('comite' , $comite)
//            ->setParameter('profil' , [6,7])
            ->orderBy('p.prioGrp', 'ASC')
            ->getQuery()
            ->getResult();

            return $db;
    }

    public function updatePrioGroupe($id, $prio_now)
    {
        $conn = $this->getEntityManager()->getConnection();
        $update = 'UPDATE tg_participation
                    SET  prio_grp = '. $prio_now . '
                    where id_participation = ' . $id;
        $conn->executeQuery($update);
        $conn->close();
    }



    public function statSollicitation($idComite, $etatSol)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->Join('p.idHabilitation', 'h')
            ->join('h.idPersonne', 'pers')
            ->join('h.idComite', 'c')
            ->LeftJoin('pers.idPersCps', 'pc')
            ->where('h.idAppel = :appel')
            ->andwhere('c.blActif = :actif' )
            ->andWhere('h.idComite = :comite' )
            ->andWhere('p.cdEtatSollicitation = :etatsol')
            ->andWhere('h.idProfil not in (:profil)') // bl != de 0
//            ->setParameter('appel', $aapg)
            ->setParameter('actif', 1)
            ->setParameter('profil', [6,7])
            ->setParameter('comite' , $idComite)
            ->setParameter('etatsol', $etatSol)
            ->orderBy('p.prioGrp', 'ASC')
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function findParticipByAppel($appel, $personne)
    {
        $db = $this
            ->createQueryBuilder('p')
            ->join('p.idComite', 'c')
            ->join('c.idAppel', 'a')
            ->where('a.idAppel = :appel')
            ->andWhere('p.idPersonne = :personne')
            ->setParameter('appel' , $appel)
            ->setParameter('personne' , $personne)
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function findPersonneAffectationResponse($comite, $profil){
        $db = $this
            ->createQueryBuilder('p')
            ->select('count(p.idPersonne) nb' , '(p.idPersonne) idPersonne', '(pers.lbNomUsage) as nom',
                '(pers.lbPrenom) as prenom')
            ->innerJoin('App:TlAvisProjet', 'tap', Join::WITH, 'p.idPersonne = tap.idPersonne')
            ->innerJoin('tap.idProjet', 'proj', Join::WITH, 'tap.idProjet = proj.idProjet')
            ->join('p.idPersonne', 'pers')
            ->where('p.idProfil = :profil')
            ->andWhere('p.idComite = :comite')
            ->setParameter('profil', $profil)
            ->setParameter('comite', $comite)
            ->groupBy('p.idPersonne', 'pers.lbNomUsage','pers.lbPrenom')
            ->getQuery()
            ->getResult();
        return $db;

    }

    public function findMembreComite($comite){
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('(tap.idPersonne) as idPersonne','(pers.lbNomUsage) as nom', '(pers.lbPrenom) as prenom')
            ->from(TlAvisProjet::class, 'tap')
            ->innerJoin('tap.idProjet', 'proj')
            ->innerJoin('tap.idPersonne', 'pers')
            ->where('proj.idComite = :comite')
            ->setParameter('comite', $comite)
            ->orderBy('tap.idPersonne', 'ASC')
            ->groupBy('tap.idPersonne', 'pers.lbNomUsage','pers.lbPrenom');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function findProjetComite($comite){
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('(tap.idProjet) as idProjet', '(proj.lbAcro) as lbAcro')
            ->from(TlAvisProjet::class, 'tap')
            ->innerJoin('tap.idProjet', 'proj')
            ->where('proj.idComite = :comite')
            ->setParameter('comite', $comite)
            ->orderBy('tap.idProjet', 'ASC')
            ->groupBy('tap.idProjet','proj.lbAcro');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function affectationData($comite){
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('tap')
            ->from(TlAvisProjet::class, 'tap')
//            ->innerJoin('tap.cdAvis', 'trap')
            ->innerJoin('tap.idProjet', 'proj')
//            ->innerJoin('tap.idPersonne', 'pers')
            ->where('proj.idComite = :comite')
            ->setParameter('comite', $comite)
//            ->groupBy('tap.i')
;
               $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function affectationAfterAvis($comite)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('ta', 'sta')
            ->from(TgAffectation::class, 'ta')
            ->innerJoin('ta.idProjet', 'proj')
            ->innerJoin('ta.idStAffect', 'sta')
            ->where('proj.idComite = :comite')
            ->setParameter('comite', $comite);
            $query = $qb->getQuery();
            $result = $query->getResult();
        return $result;

    }

    public function projWhithCmteAffec($comite,array $st_affect){
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('ta')
            ->from(TgAffectation::class, 'ta')
            ->innerJoin('ta.idStAffect', 'sta')
            ->innerJoin('ta.idProjet', 'proj')
            ->where('proj.idComite = :comite')
            ->andWhere('sta.symbole IN (:st_affect)')
            ->setParameter('st_affect', $st_affect)
            ->setParameter('comite', $comite)
//            ->orderBy('ta.idProjet','ASC')
        ;
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

//    public function crosstabTlProjet(){
//        $tabs = $this->findMembreComite(6);
//        $per[] ='projet integer';
//        foreach ($tabs as $kay => $tab){
//            $nomPren = 'p'.$kay;
//            $per[]=$nomPren. ' text';
//
//        };
//        $t = implode(',', $per);
//        $conn = $this->getEntityManager()->getConnection();
//        $data = "select * from crosstab('select id_projet, id_personne, tap.code_avis from tl_avis_projet
//                inner join tr_avis_projet tap on tl_avis_projet.cd_avis = tap.cd_avis  ORDER BY 1',
//                 'SELECT DISTINCT id_personne  FROM tl_avis_projet ORDER BY 1')
//                 AS  ($t);";
//        $datas =  $conn->executeQuery($data);
//        return $datas->fetchAll();
//    }

}