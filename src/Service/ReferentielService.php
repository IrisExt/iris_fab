<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

/**
 * Class ReferentielService.
 */
class ReferentielService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $host2;

    /**
     * GeoDBService constructor.
     * @param Client $client
     * @param string $host
     * @param string $key
     * @param string $host2
     */
    public function __construct(Client $client, string $host, string $key, string $host2)
    {
        $this->client = $client;
        $this->host = $host;
        $this->key = $key;
        $this->host2 = $host2;
    }

    /**
     * @param int|null $rnsr
     *
     * @return array
     */
    public function getRnsrIds(string $rnsr)
    {
        $response = $this->client->get(
            $this->host.'/rnsr',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                     'include' => 'num_nat_struct',
                     'filters' => 'num_nat_struct,sw,'.$rnsr,
                ],
            ]
        );

        $rnsrs = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $num_rnsr) {
            $rnsrs[$key] = $num_rnsr->num_nat_struct;
        }

        return $rnsrs;
    }

    /**
     * @return array
     */
    public function getListeTutelles(string $rnsr)
    {
        $response = $this->client->get(
            $this->host.'/structetab',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'id_structure,cleetab,id_etab,nattutetab,idstructetab,label,numero,andebut,anfin',
                    'filters' => 'num_nat_struct,eq,'.$rnsr,
                ],
            ]
        );

        $etabs = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $etab) {
            $etabs[] = ['id' => $etab->cleetab, 'text' => $etab->id_etab];
        }

        return $etabs;
    }

    /**
     * @param string $rnsr
     *
     * @return array
     */
    public function getInfosTutelles(string $idTutelle)
    {
        $response = $this->client->get(
            $this->host.'/etablissement',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'include=cleetab,sigle,libelle,numuai,sirensiret,siren',
                    'filters' => 'cleetab,eq,'.$idTutelle,
                ],
            ]
        );

        $etab = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $tut) {
            $etab = [
                'libelle' => $tut->libelle,
                'sirensiret' => $tut->sirensiret,
                'numuai' => $tut->numuai,
            ];
        }

        return $etab;
    }

    /**
     * @param string $rnsr
     *
     * @return array
     */
    public function getLibelle(string $idTutelle)
    {
        $response = $this->client->get(
            $this->host.'/etablissement',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'include=cleetab,sigle,libelle,numuai,sirensiret,siren',
                    'filters' => 'cleetab,eq,'.$idTutelle,
                ],
            ]
        );

        $libelle = '';

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $tut) {
            $libelle = $tut->libelle;
        }

        return $libelle;
    }

    /**
     * @param string $rnsr
     *
     * @return array
     */
    public function getCleWithSiret(string $siret)
    {
        $response = $this->client->get(
            $this->host.'/etablissement',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'include=id,cleetab,sigle,libelle,numuai,sirensiret,siren',
                    'filters' => 'sirensiret,eq,'.$siret,
                ],
            ]
        );

        $cletab = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $tut) {
            $cletab = $tut->cleetab;
        }

        return $cletab;
    }

    /**
     * @return array
     */
    public function getListeDelegations(string $cleetab)
    {
        $response = $this->client->get(
            $this->host.'/delegations',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'eatb_id,nom,sigle,siret',
                    'filters' => 'cleetab,eq,'.$cleetab,
                ],
            ]
        );

        $etabs = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $etab) {
            $etabs[] = ['id' => $etab->nom, 'text' => $etab->nom];
        }

        return $etabs;
    }

    /**
     * @return array
     */
    public function getListeSirets(string $siret)
    {
        $response = $this->client->get(
            $this->host2.'/reftiers',
            [
              //  'verify' => false,
                'http_errors' => true,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host2,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                   // 'client' => 'iris',
                   // 'key' => '305305305305',
                    'include' => 'siret,raison_sociale,enseigne,adresse,complement_d_adresse,code_postal,ville,id_pays,id_categories_anr',
                     'filters' => 'siret,sw,'.$siret,
                ],
            ]
        );

        $sirets = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $siren) {
            $sirets[$key] = $siren->siret;
        }

        return $sirets;
    }

    /**
     * @return array
     */
    public function getDatasSiret(string $siret)
    {
        $response = $this->client->get(
            $this->host2.'/reftiers',
            [
               // 'verify' => false,
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host2,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                   // 'client' => 'iris',
                   // 'key' => '305305305305',
                    'include' => '',
                    'filters' => 'siret,sw,'.$siret,
                ],
            ]
        );

        $sirets = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $siren) {
            $sirets = [
                'raison_sociale' => $siren->raison_sociale,
                'sigle_anr' => $siren->sigle_anr,
                'adresse' => $siren->adresse,
                'complement_d_adresse' => $siren->complement_d_adresse,
                'code_postal' => $siren->code_postal,
                'ville' => $siren->ville,
                'id_pays' => $siren->id_pays,
            ];
        }

        return $sirets;
    }

    /**
     * @return array
     */
    public function getInfoRnsr(string $rnsr)
    {
        $response = $this->client->get(
            $this->host.'/rnsr',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
                'query' => [
                    'include' => 'num_nat_struct,intitule,sigle,adr_postale,ville_postale,code_postal',
                    'filters' => 'num_nat_struct,eq,'.$rnsr,
                ],
            ]
        );

        $rnsrs = [];

        foreach (json_decode($response->getBody()->getContents())->datas as $key => $rnsr_) {
            $rnsrs = [
                'num_nat_struct' => $rnsr_->num_nat_struct,
                'intitule' => $rnsr_->intitule,
                'sigle' => $rnsr_->sigle,
                'adr_postale' => $rnsr_->adr_postale,
                'ville_postale' => $rnsr_->ville_postale,
                'code_postal' => $rnsr_->code_postal,
            ];
        }

        return $rnsrs;
    }
}
