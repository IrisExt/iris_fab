<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\TgAdresse;
use App\Entity\TgOrganisme;
use App\Entity\TlPersOrg;
use App\Entity\TrPays;
use App\Entity\TrRnsr;
use App\Entity\TrSiret;
use Doctrine\ORM\EntityManagerInterface;

class OrganismeService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * OrganismeService constructor.
     */
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function verifAndAddOrganisme(
            $rnsr = null,
            $siret = null,
            $lbnomfr = null,
            $lbLaboratoir = null,
            $numero_unite = null,
            $sigle = null,
            $lb_adress = null,
            $compl_adress = null,
            $postal_code = null,
            $ville = null,
            TrPays $trPays = null
        ){

        $rnsr_ob = $this->em->getRepository(TrRnsr::class)->findOneBy(['cdRnsr' => $rnsr])?: null;
        $siret_ob = $this->em->getRepository(TrSiret::class)->findOneBy(['siret' => $siret])?: null;


        $adresse_rnsr = $rnsr_ob && $rnsr_ob->getIdAdresse() != null ? $rnsr_ob->getIdAdresse() : new TgAdresse();
        $adresse_siret = $siret_ob && $siret_ob->getIdAdresse() != null ? $siret_ob->getIdAdresse(): new TgAdresse();
        $adresse = $rnsr ? $adresse_rnsr :  $adresse_siret;

        $adresse
            ->setLbAdresse($lb_adress)
            ->setLbComplAdresses($compl_adress)
            ->setCd($postal_code)
            ->setTypAdr('org')
            ->setVille($ville)
            ->setCdPays($trPays);
        $this->em->persist($adresse);
        $this->em->flush();

        $response =  ($rnsr != null) ? $this->searchOrAddRnsr($rnsr_ob,$siret_ob,$adresse, $rnsr, $siret, $lbnomfr,$numero_unite,$lbLaboratoir)
                                : $this->searchOrAddSiret($siret_ob,$adresse, $siret, $lbnomfr,$sigle) ;

            return $response;
        }


    public function searchOrAddRnsr($rnsr_ob,$siret_ob,$adresse,$rnsr, $siret, $lbnomfr,$numero_unite,$lbLaboratoir){

        if(!$rnsr_ob) {
            $rnsr_ob = new TrRnsr();
            $rnsr_ob
                ->setCdRnsr($rnsr)
                ->setLbLaboratoire($lbLaboratoir)
                ->setIdAdresse($adresse);
            $this->em->persist($rnsr_ob);

        }

        if(!$siret_ob){
            $siret_ob = new TrSiret();
            $siret_ob->setSiret($siret);
            $siret_ob->setCodeUnite($numero_unite);

            $this->em->persist($siret_ob);
        }

        $organisme_ob = $this->em->getRepository(TgOrganisme::class)->findOneBy(['cdRnsr' => $rnsr_ob, 'siret' => $siret_ob])?: null;

        if(!$organisme_ob){
                $organisme_ob = new TgOrganisme();
                $organisme_ob->setCdRnsr($rnsr_ob);
                $organisme_ob->setSiret($siret_ob);
                $organisme_ob->setLbLaboratoire($lbLaboratoir);
                $organisme_ob->setLbNomFr($lbnomfr);

                $this->em->persist($organisme_ob);
        }
        $this->em->flush();

        return $organisme_ob;
    }

    private function searchOrAddSiret($siret_ob,$adresse, $siret, $lbnomfr, $sigle)
    {
        if(!$siret_ob) {
            $siret_ob = new TrSiret();
            $siret_ob
                ->setSiret($siret)
                ->setIdAdresse($adresse)
                ->setSigle($sigle);
            $this->em->persist($siret_ob);
        }else{
            $siret_ob
                ->setIdAdresse($adresse)
                ->setSigle($sigle);
            $this->em->persist($siret_ob);
        }
          $organisme_ob = $this->em->getRepository(TgOrganisme::class)->findOneBy(['cdRnsr' => null, 'siret' => $siret_ob])?:null;

        if(!$organisme_ob){
            $organisme_ob = new TgOrganisme();
            $organisme_ob->setSiret($siret_ob);
            $organisme_ob->setLbNomFr($lbnomfr);

            $this->em->persist($organisme_ob);
        }

        $this->em->flush();

        return $organisme_ob;

    }

    public function addTlOrgPersonne($organisme, $personne, $typeOrg, $lbService = null){
        $tlPersOrg = new TlPersOrg();
        $tlPersOrg
            ->setIdPersonne($personne)
            ->setIdOrganisme($organisme)
            ->setTypeOraganisme($typeOrg)
            ->setLbService($lbService);
        $this->em->persist($tlPersOrg);
        $this->em->flush();
    }

}