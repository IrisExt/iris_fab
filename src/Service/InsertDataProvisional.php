<?php

namespace App\Service;

use App\Entity\TgMcCes;
use App\Entity\TgMotCleErc;
use App\Entity\TgParametre;
use App\Entity\TlAvisPossibles;
use App\Entity\TlMcErcAppel;
use App\Entity\TrAvisProjet;
use Doctrine\ORM\EntityManagerInterface;

class InsertDataProvisional
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * InsertDataProvisional constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function DataAapg($tgAppelProj): void
    {
        ////////////////////////////////////// Remplir tg_parametre et mot cle erc / test , -- Provisoire -- //////////////////////////////////////
        $tgparam = $this->em->getRepository(TgParametre::class)->findAll();

        if (!$tgparam) {
            $tgparm = new TgParametre();
            $tgparm->setLbCode('NB_MC_LIBRE_CV_MAX')
                ->setLbValeur(5);
            $this->em->persist($tgparm);
            $tgparm2 = new TgParametre();
            $tgparm2->setLbCode('NB_MC_ERC_CV_MAX')
                ->setLbValeur(3);
            $this->em->persist($tgparm2);
        }

        $params = ['FMT_DOC_SC', 'NB_PAGES_MAX', 'FMT_AN_PRE_PROPOSOTI', 'TAILLE_FIC_AN_MAX', 'NB_PAGES_AN_MAX', 'NB_CAR_MAX_FR', 'TAILLE_FIC_MAX', 'NB_CAR_MAX_EN', 'NB_CO_FI_RECH_MAX',  'NB_MC_CES_MAX', 'NB_POL_COMP_MAX', 'NB_STRUCT_RECH_MAX', 'NB_MC_ERC_MAX'];
        $values = ['PDF', '3', 'pdf', '10', '5', '1000', '10', '1000', '3', '3', '4', '4', '4'];
        foreach ($params as $key => $param) {
            $tgparm = new TgParametre();
            $tgparm->setLbCode($param)
                ->setIdAppel($tgAppelProj)
                ->setLbValeur($values[$key]);
            $this->em->persist($tgparm);
        }

        $motCleErcs = $this->em->getRepository(TgMotCleErc::class)->findAll();
        foreach ($motCleErcs as $motcleErc) {
            $tlMotCleAppel = new TlMcErcAppel();
            $tlMotCleAppel->setIdAppel($tgAppelProj)
                ->setIdMcErc($motcleErc);
            $this->em->persist($tlMotCleAppel);
        }

        ////////////////////////////////////////////////////// FIN  tg_parametre and motCleErc/////////////////////////////////////////////////
    }

    public function DataComite($Comite): void
    {
        ////////////////////////////////////// Remplir mc ces et avis possible  pour test , -- Provisoir -- //////////////////////////////////////
        $tabMcCes = ['Atmosphère', 'Océan', 'Interfaces', 'Changement climatiques', 'Surfaces continentales'];
        foreach ($tabMcCes as $mcces) {
            $tgMcCes = new TgMcCes();
            $tgMcCes->setIdComite($Comite);
            $tgMcCes->setLbMcCes($mcces);
            $this->em->persist($tgMcCes);
        }

        $trAvisProj = [1, 2, 3, 4, 5];
        $tabMin = [30, 10, 0, 20, 10];
        $tabMax = [80, 90, 50, 80, 100];
        foreach ($tabMin as $key => $min) {
            $travis = $this->em->getRepository(TrAvisProjet::class)->find($trAvisProj[$key]);
            $tlAvisPossible = new TlAvisPossibles();
            $tlAvisPossible->setCdAvis($travis)
                ->setIdComite($Comite)
                ->setPourcentMin($min)
                ->setPourcentMax($tabMax[$key]);
            $this->em->persist($tlAvisPossible);
        }
        //////////////////////////////////////////////// FIn //////////////////////////////////////////////
    }
}
