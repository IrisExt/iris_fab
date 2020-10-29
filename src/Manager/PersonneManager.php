<?php


namespace App\Manager;


use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use App\Entity\TrGenre;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;

class PersonneManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PersonneRepository
     */
    private $personneRepository;

    /**
     * PersonneManager constructor.
     * @param EntityManagerInterface $em
     * @param PersonneRepository $personneRepository
     */
    public function __construct (EntityManagerInterface $em, PersonneRepository $personneRepository)
    {
        $this->em = $em;
        $this->personneRepository = $personneRepository;
    }

    public function setTgPersonne(string $nom, string $prenom, TrGenre $trGenre = null, TgPersCps $tgPersCps = null)
    {
        $tgPersonne = new TgPersonne();
        $tgPersonne
            ->setIdGenre($trGenre)
            ->setLbNomUsage($nom)
            ->setLbPrenom($prenom)
            ->setIdPersCps($tgPersCps)
            ->setCvRenseigne(false);
        $this->em->persist($tgPersonne);

        return $tgPersonne;
    }

}