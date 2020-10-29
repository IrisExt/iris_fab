<?php


namespace App\Repository;


use App\Entity\TgPersonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;


class PersonneRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * PersonneRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TgPersonne::class);
        $this->entityManager = $entityManager;
    }

    public function findPersonneRecherche(Request $request, $comite) :query
    {

        $query = $this->findPersonneCes($comite);

        if($request->get('genre')){
            $query = $query->join('p.idGenre', 'c')
                ->andwhere('c.idGenre = :genre')
                ->setParameter('genre', $request->get('genre'));
        }
        if($request->get('organisme')){
            $query
                ->andwhere('Lower(ps.lbOrganisme) like :organisme')
                ->setParameter('organisme' , strtolower('%'.$request->get('organisme').'%'));
        }
        if($request->get('personne')){
            $query = $query->andwhere('p.idPersonne = :idpersonne')
                ->setParameter('idpersonne', $request->get('personne'));
        }
        if($request->get('motcle')){
            $query = $query->join('p.idMcCps', 'm')
                ->andwhere('m.idMcCps = :motcle')
                ->setParameter('motcle',$request->get('motcle') );
        }
        if($request->get('email')){
            $query = $query->andwhere('p.idPersonne = :idpersonne')
                ->setParameter('idpersonne', $request->get('email'));
        }

        return $query->getQuery();
    }

    public function findPersonneCes($comite) :QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->join('p.idPersCps', 'ps')
            ->where('p.idPersCps is not null');

    }


    public function findParticipPersInAppel($appel, $personne){
        $qb = $this->createQueryBuilder('p')
            ->join('p.idHabilitation', 'h')
            ->where('p.idPersonne = :personne')
            ->andWhere('h.idAppel = :appel')
            ->setParameter('personne', $personne)
            ->setParameter('appel', $appel)
            ->getQuery()
            ->getResult();
        return $qb;
    }

    public function listMsgCmt($comiteObject, $personne, $appel, $participant){

        $qb =  $this->createQueryBuilder('p')
            ->join('p.idHabilitation' ,'hp')
            ->leftjoin('hp.idAppel', 'app')
            ->leftJoin('hp.idComite', 'com');
        $exp =  $qb->expr()->eq('hp.idProfil', 10);
        $exp1 = $qb->expr()->orX($exp, $qb->expr()->andX($qb->expr()->eq('com.idComite', ':comite'), $qb->expr()->in('hp.idProfil', ':profil')));
        $exp2 =  $qb->expr()->orX($exp1, $qb->expr()->andX($qb->expr()->eq('hp.idProfil', 1),$qb->expr()->eq('app.idAppel', ':appel')));
        $exp3 = $qb->expr()->andX($exp2, $qb->expr()->neq('p.idPersonne', ':personne'));
        $qb->where($exp3);
        $qb->setParameter('appel', $appel);
        $qb->setParameter('comite', $comiteObject);
        $qb->orderBy('hp.idProfil', 'ASC');

        if(null == $participant){
            $qb->setParameter('profil',[6,7,4]);
        }else{
            $qb->setParameter('profil',[6,7,4,8]);
        }
        $qb->setParameter('personne', $personne);
        return $qb;
    }

    public function msgParticipant($personne){
        $qb = $this->createQueryBuilder('p')
            ->join('p.idHabilitation' ,'hp')
            ->where('p.idPersonne = :personne')
            ->setParameter('personne', $personne);
        return $qb;
    }

    /**
     * @param $partenaireId
     * @return QueryBuilder
     */
    public function findbyPartenaire($partenaireId)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('p')
            ->from(TgPersonne::class, 'p')
            ->leftJoin(
                tl_pers_part,
                'tpp',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'p.idPersonne = tpp.idPersonne'
            )
            ->where('tpp.idPartenaire = :idPartenaire')
            ->setParameter(':idPartenaire', $partenaireId)
        ;
        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;

    }

    public function findPersonnesByString($str)
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(TgPersonne::class, 'p')
            ->andwhere('lower (p.lbNomUsage) LIKE lower(:str) OR lower(p.lbPrenom) LIKE lower(:str)')
            ->setParameter('str', '%'.$str.'%')
            ->orderBy('p.lbNomUsage')
            ->getQuery();

        return $qb->execute();

    }

}