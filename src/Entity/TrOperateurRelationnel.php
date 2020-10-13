<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrOperateurRelationnel
 *
 * @ORM\Table(name="tr_operateur_relationnel")
 * @ORM\Entity
 */
class TrOperateurRelationnel
{

    /**
     * @var int
     *
     * @ORM\Column(name="id_operateur", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_operateur_relationnel_id_operateur_seq", allocationSize=1, initialValue=1)
     */
    private $idOperateur;

    /**
     * @var string
     *
     * @ORM\Column(name="cd_operateur", type="string", length=50, nullable=false)
     */
    private $cdOperateur;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=false)
     */
    private $lbNomEn;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\TrCritere", mappedBy="idOperateur", cascade={"remove"})
     */
    private $idCritere;

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdCritere(): \Doctrine\Common\Collections\Collection
    {
        return $this->idCritere;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idCritere
     */
    public function setIdCritere(\Doctrine\Common\Collections\Collection $idCritere): void
    {
        $this->idCritere = $idCritere;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCritere = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdOperateur(): int
    {
        return $this->idOperateur;
    }

    /**
     * @return string
     */
    public function getCdOperateur(): string
    {
        return $this->cdOperateur;
    }

    /**
     * @param string $cdOperateur
     */
    public function setCdOperateur(string $cdOperateur): void
    {
        $this->cdOperateur = $cdOperateur;
    }

    /**
     * @return string
     */
    public function getLbNomFr(): string
    {
        return $this->lbNomFr;
    }

    /**
     * @param string $lbNomFr
     */
    public function setLbNomFr(string $lbNomFr): void
    {
        $this->lbNomFr = $lbNomFr;
    }

    /**
     * @return string
     */
    public function getLbNomEn(): string
    {
        return $this->lbNomEn;
    }

    /**
     * @param string $lbNomEn
     */
    public function setLbNomEn(string $lbNomEn): void
    {
        $this->lbNomEn = $lbNomEn;
    }

    public function __toString()
    {
        return $this->getLbNomFr();
    }

}
