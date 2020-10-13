<?php


namespace App\Repository;


use App\Entity\TgMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessageRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TgMessage::class);
    }


    public function findAllMesPers()
    {
        $db = $this
            ->createQueryBuilder('m')
//            ->Join('m.idComite', 'b')
//            ->Join('m.destinataire', 'd')
//            ->Join('m.emetteur', 'e')
            ->orderBy('m.idMessage', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();

        return $db;
    }

    public function findMessagePersHabi($idComite)
    {

        $db = $this
            ->createQueryBuilder('m')
//            ->leftJoin('App:TgHabilitation' , 'h', Join::WITH, 'm.idComite = h.idComite')
            ->where('m.idComite = :idcomite')
            ->andWhere('m.idParticipation is NULL')
            ->setParameter('idcomite', $idComite)
            ->orderBy('m.idMessage', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(3);
         return new Paginator($db);
    }

    public function findMessagePartici($idParticipation)
    {
        $db = $this
            ->createQueryBuilder('m')
            ->where('m.idParticipation = :participation')
            ->setParameter('participation', $idParticipation)
            ->orderBy('m.idMessage', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $db;

    }

}