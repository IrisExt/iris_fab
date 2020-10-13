<?php

namespace App\Repository;

use App\Entity\TgHabilitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method TgHabilitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgHabilitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgHabilitation[]    findAll()
 * @method TgHabilitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabilitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgHabilitation::class);
    }

    /**
     * @param $personne
     *
     * @return mixed
     */
    public function habilitationAllProfil($personne)
    {
        $db = $this
            ->createQueryBuilder('h');

        return $db
            ->leftjoin('h.idComite', 'c')
            ->leftJoin('h.idAppel', 'a')
            ->where($db->expr()->orX($db->expr()->isNotNull('c.idComite'), $db->expr()->isNotNull('a.idAppel')))
            ->andWhere($db->expr()->andX($db->expr()->eq('h.idPersonne', ':personne'), $db->expr()->eq('h.blSupprime', 1)))
            ->setParameter('personne', $personne)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $idPersonne
     *
     * @return mixed
     *               un group by de profil utilisateur
     */
    public function profilUtilisateur($idPersonne)
    {
        return $this->createQueryBuilder('h')
            ->select('count(h.idPersonne) nb', '(p.idProfil) idprofilUser', 'p.lbProfil nomprofilUser')
            ->join('h.idProfil', 'p')
            ->where('h.idPersonne = :idpersonne')
            ->andWhere('h.blSupprime = 1')
            ->setParameter(':idpersonne', $idPersonne)
            ->groupBy('p.idProfil')
            ->getQuery()
            ->getResult();
    }

//    public function profilPersonne($idPersonne)
//    {
//        $db = $this
//            ->createQueryBuilder('a')
//            ->where('a.idPersonne = :idpersonne')
//            ->andWhere('a.blSupprime = 1')
//            ->setParameter(':idpersonne', $idPersonne)
    ////            ->orderBy('a.idAppel', 'DESC')
//            ->getQuery()
//            ->getResult();
//
//        return $db;
//    }

    public function AppelPersonne($idPersonne)
    {
        return $this
                    ->createQueryBuilder('a')
                    ->select('(a.idAppel)')
                    ->where('a.idPersonne = :idpersonne')
                    ->setParameter(':idpersonne', $idPersonne)
                    ->groupBy('a.idAppel')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param $appel
     * @param $user
     *
     * @return mixed
     *               retourne personnes dans un appel en phase courante
     */
    public function findSessionActive($appel, $user)
    {
        return $this
            ->createQueryBuilder('a')
            ->join('a.idPhase', 'b')
            ->where('a.idPersonne = :user')
            ->andwhere('a.idAppel = :appel')
            ->andwhere('b.blPhaseCourante = :etat')
            ->setParameter('user', $user)
            ->setParameter('appel', $appel)
            ->setParameter('etat', true)
            ->getQuery()
            ->getResult();
    }

    public function PersonnehabiliteComite($comite)
    {
        return $this
            ->createQueryBuilder('a')
            ->Join('a.idComite', 'c')
            ->where('c.idComite = :comite')
            ->setParameter('comite', $comite)
            ->getQuery()
            ->getResult();
    }

    public function habilitePersonneComite($comite, $pers)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.idComite = :comite')
            ->andwhere('a.idPersonne= :pers')
            ->setParameter('comite', $comite)
            ->setParameter('pers', $pers)
            ->getQuery()
            ->getResult();
    }

    public function profilPersComite($phase, $comite, $profil)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.idComite = :comite')
            ->andwhere('a.idPhase= :phase')
            ->andwhere('a.idProfil= :profil')
            ->setParameter('phase', $phase)
            ->setParameter('comite', $comite)
            ->setParameter('profil', $profil)
            ->getQuery()
            ->getResult();
    }

    public function userhabilitationComite($user)
    {
        return $this
            ->createQueryBuilder('p')
            ->Join('p.idComite', 'c')
            ->join('p.idPersonne', 'pers')
//            ->addselect('c')
//            ->addselect('(p.idPhase)')
            ->where('c.blActif = 1')
            ->andWhere('p.blSupprime = :blsupprime') // bl != de 0
            ->andWhere('p.idPersonne = :idpersonne')
            ->setParameter('blsupprime', 1)
            ->setParameter('idpersonne', $user)
//            ->groupBy('p.idComite')
//            ->groupBy('p.idPhase')
//            ->orderBy('p.idComite' , 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function userhabilitationAppel($user)
    {
        return $this
            ->createQueryBuilder('p')
            ->Join('p.idAppel', 'a')
            ->join('p.idPersonne', 'pers')
            ->andWhere('p.blSupprime = :blsupprime')
            ->andWhere('p.idPersonne = :idpersonne')
            ->setParameter('blsupprime', 1)
            ->setParameter('idpersonne', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $personne
     * @return array|null
     */
    public function userParticipComite($personne): ?array
    {
        $userComites = $this->userhabilitationComite($personne);
        // les accès  pour les participants
        $comitePart = null;
        // recupèrer tous les comités dans un tab
        if (!empty($userComites)) {
            foreach ($userComites as $userComite) {
                foreach ($userComite->getIdComite() as $cmt) {
                    $comitePart[] = $cmt;
                }
            }
        }

        return $comitePart;
    }

    public function piloteAppelAcces($appel, $personne): bool
    {
        $db = $this
            ->createQueryBuilder('h')
            ->join('h.idAppel', 'a')
            ->where('h.idPersonne = :pers')
            ->andWhere('a.idAppel = :appel')
            ->andWhere('h.idProfil = 1')
            ->setParameter('pers', $personne)
            ->setParameter('appel', $appel)
            ->getQuery()->getResult();
        // si vide flase

        return empty($db) ? false : true;
    }

    public function allProfil($idPersonne)
    {
        return $this
            ->createQueryBuilder('a')
            ->join('a.idAppel', 'app')
            ->join('a.idPhase', 'pha')
            ->leftjoin('a.idComite', 'com')
            ->addSelect('app', 'pha', 'com')
            ->where('a.idPersonne = :idpersonne')
            ->andWhere('a.blSupprime = 1')
            ->setParameter(':idpersonne', $idPersonne)
            ->getQuery()
            ->getResult();
    }

    public function pilotebyAppel($appel, $profil)
    {
        return $this
            ->createQueryBuilder('h')
            ->join('h.idAppel', 'a')
            ->where('h.idProfil = :profil')
            ->andWhere('a.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->setParameter('profil', $profil)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findProfilByComite($comite, $profil)
    {
        return $this
            ->createQueryBuilder('h')
            ->leftJoin('h.idComite', 'c')
            ->where('h.idProfil = :profil')
            ->andWhere('c.idComite = :comite')
            ->setParameter('comite', $comite)
            ->setParameter('profil', $profil)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $personne
     * @param $appel
     *
     * @return mixed
     */
    public function profilByAppel($personne, $appel)
    {
        return $this
            ->createQueryBuilder('h')
            ->join('h.idAppel', 'c')
            ->where('h.idPersonne = :personne')
            ->andwhere('c.idAppel = :appel')
            ->setParameter('personne', $personne)
            ->setParameter('appel', $appel)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $personne
     * @param $comite
     *
     * @return mixed
     */
    public function profilByComite($personne, $comite)
    {
        return $this
            ->createQueryBuilder('h')
            ->join('h.idComite', 'c')
            ->where('h.idPersonne = :personne')
            ->andwhere('c.idComite = :comite')
            ->setParameter('personne', $personne)
            ->setParameter('comite', $comite)
            ->getQuery()
            ->getResult();
    }

    public function habiliGroupByProfil($personne)
    {
        $db = $this
            ->createQueryBuilder('h')
            ->addSelect('count(h.idProfil) as profil', 'h.idProfil');

        return $db
            ->leftjoin('h.idComite', 'c')
            ->leftJoin('h.idAppel', 'a')
            ->where($db->expr()->orX($db->expr()->isNotNull('c.idComite'), $db->expr()->isNotNull('a.idAppel')))
            ->andWhere($db->expr()->andX($db->expr()->eq('h.idPersonne', ':personne'), $db->expr()->eq('h.blSupprime', 1)))
            ->setParameter('personne', $personne)
            ->groupBy('profil')
            ->getQuery()
            ->getResult();
    }

    public function habilByComiteAndAppel($comite, $appel)
    {
        $db = $this
            ->createQueryBuilder('h');

        return $db
            ->leftjoin('h.idComite', 'c')
            ->leftJoin('h.idAppel', 'a')
            ->where($db->expr()->orX($db->expr()->orX($db->expr()->eq('c.idComite', $comite), $db->expr()->eq('a.idAppel', $appel)), $db->expr()->eq('h.idProfil', 10)))
            ->andWhere($db->expr()->eq('h.blSupprime', 1))
            ->getQuery()
            ->getResult();
    }

    public function habilByComiteAndAppelAndPers($comite, $appel, $personne)
    {
        $db = $this
            ->createQueryBuilder('h');

        return $db
            ->leftjoin('h.idComite', 'c')
            ->leftJoin('h.idAppel', 'a')
            ->Where($db->expr()->andX($db->expr()->eq('h.blSupprime', 1), $db->expr()->eq('h.idPersonne', $personne)))
            ->andwhere($db->expr()->orX($db->expr()->orX($db->expr()->eq('c.idComite', $comite), $db->expr()->eq('a.idAppel', $appel)), $db->expr()->eq('h.idProfil', 10)))
            ->getQuery()
            ->getResult();
    }

    public function particiByHabili($personne)
    {
        return $this
            ->createQueryBuilder('h')
            ->InnerJoin('App:TgParticipation', 'p', Join::WITH, 'p.idPersonne = h.idPersonne')
            ->where('h.idPersonne = :personne')
            ->setParameter('personne', $personne)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $projet
     *
     * @return int|mixed|string
     */
    public function habiMbreByProjet($projet)
    {
        //            ->getOneOrNullResult();
        return $this
            ->createQueryBuilder('h')
            ->innerJoin('h.idProjet', 'p')
             ->where('p.idProjet = :projet')
             ->andWhere('h.idProfil IN (17,18)')
            ->setParameter('projet', $projet)
            ->getQuery()->getResult();
    }

    /**
     * @param $personne
     *
     * @param array $profil
     * @return int|mixed|string
     */
    public function evalExpertiseHabi($personne, array $profil)
    {
        return $this
            ->createQueryBuilder('h')
            ->innerJoin('h.idComite', 'c')
            ->where('h.idProfil in (:profil)')
            ->andWhere('h.idPersonne = :personne')
            ->setParameter('personne', $personne)
            ->setParameter('profil', $profil)
            ->getQuery()
            ->getResult();
    }
}
