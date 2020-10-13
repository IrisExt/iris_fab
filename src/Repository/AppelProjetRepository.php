<?php

namespace App\Repository;

use App\Entity\TgAppelProj;
use App\Entity\TrNiveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * Class AppelProjetRepository
 * @method TgAppelProj|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgAppelProj|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgAppelProj[]    findAll()
 * @method TgAppelProj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @package App\Repository
 */
class AppelProjetRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgAppelProj::class);
    }

    /**
     * retourne l'entity du dernier AAPG
     * @return mixed
     *
     */
    public function dernierAppel()
    {
        $db = $this
            ->createQueryBuilder('a')
            ->orderBy('a.idAppel', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        return $db[0];
    }

    public function findnotby($id){
        $db = $this
            ->createQueryBuilder('a')
            ->where('a.idAppel!= :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $db;
    }

    public function findAllAppelEnCours()
    {
        $db = $this->createQueryBuilder('a')
            ->join('a.niveauEnCours' , 'n')
            ->where('n.dhDebut <= :now')
            ->andWhere('n.dhFin >= :now')
            ->setParameter('now', new \DateTime('now'))
            ->getQuery()
            ->getResult();
            return $db;
    }

    public function findDateClosAppel($appel)
    {
        $db = $this->createQueryBuilder('a')
            ->join('a.niveauEnCours' , 'n')
            ->andWhere('n.dhFin >= :now')
            ->andWhere('a.idAppel = :appel')
            ->setParameter('appel', $appel)
            ->setParameter('now', new \DateTime('now'))
            ->getQuery()
            ->getResult();
        return $db;

    }

    public function AppelInTimeSlot($appel){
        $db = $this->createQueryBuilder('a')
            ->join('a.niveauEnCours' , 'n')
            ->andWhere('a.idAppel = :appel')
            ->andwhere(':now BETWEEN n.dhDebut AND n.dhFin')
            ->setParameter('appel', $appel)
            ->setParameter('now', new \DateTime('now'))
            ->getQuery()->getResult();
        return $db;


    }

    /**
     * @param int $niveau
     * @return int|mixed|string
     * retourne appels par type iveau choisi (soumission = 1, eval = 2,..)
     */
    public function AppelWithNiveau(int $niveau){
       $trNiveau =  $this->getEntityManager()->getRepository(TrNiveau::class)->find($niveau);
        $db = $this
            ->createQueryBuilder('a')
            ->join('a.niveauEnCours' , 'n')
            ->andwhere(':now BETWEEN n.dhDebut AND n.dhFin')
            ->andWhere('n.idTypeNiveu = :niveau')
            ->setParameter('niveau', $trNiveau)
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('a.idAppel', 'ASC')
            ->getQuery()->getResult();

        return $db;
    }



//
//select * from tg_projet
//inner join tg_appel_proj tap on tg_projet.id_appel = tap.id_appel
//inner join tg_niveau_phase tnp on tap.id_appel = tnp.id_appel
//inner join tg_phase tp on tnp.id_phase = tp.id_phase
//inner join tr_phase as trp on tp.id_phase_ref = trp.id_phase_ref
//inner join tr_niveau tn on tnp.id_type_niveu = tn.id_type_niveu
//where  porteur  = 16
//and tn.id_type_niveu = 1
//and trp.id_phase_ref = 1
}