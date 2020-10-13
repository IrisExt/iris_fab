<?php

namespace App\Repository;

use App\Entity\TgResume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TgResume|null find($id, $lockMode = null, $lockVersion = null)
 * @method TgResume|null findOneBy(array $criteria, array $orderBy = null)
 * @method TgResume[]    findAll()
 * @method TgResume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TgResumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgResume::class);
    }

    /**
     * @param string $resume
     * @param int $idlangue
     */
    public function setResume(string $resume, int $idProjet, int $idlangue)
    {
        $qb = $this->createQueryBuilder('r');
        $q = $qb->update('App:TgResume', 'r')
            ->set('r.lbTexte', ':resume')
            ->where('r.idProjet = :idProjet')
            ->andWhere('r.idLangue = :idlangue')
            ->setParameter('resume', $resume)
            ->setParameter('idProjet', $idProjet)
            ->setParameter('idlangue', $idlangue)
            ->getQuery();
         $q->execute();
    }

    // /**
    //  * @return TgResume[] Returns an array of TgResume objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TgResume
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
