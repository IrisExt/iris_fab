<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrCritere
 *
 * @ORM\Table(name="tr_critere")
 * @ORM\Entity
 */
class TrCritere
{

    /**
     * @var int
     *
     * @ORM\Column(name="id_critere", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_critere_id_critere_seq", allocationSize=1, initialValue=1)
     */
    private $idCritere;

    /**
     * @var string
     *
     * @ORM\Column(name="input_critere", type="string", length=50, nullable=false)
     */
    private $inputCritere;

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
     * @var string
     *
     * @ORM\Column(name="lb_nom_table_valeur", type="string", length=50, nullable=false)
     */
    private $lbNomTableValeur;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_critere", type="string", length=10, nullable=false)
     */
    private $typCritere;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_search", type="string", length=10, nullable=false)
     */
    private $typSearch;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\TrOperateurRelationnel", inversedBy="idCritere")
     * @ORM\JoinTable(name="tl_critere_operateur",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_critere", referencedColumnName="id_critere")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_operateur", referencedColumnName="id_operateur")
     *   }
     * )
     */
    private $idOperateur;

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdOperateur(): \Doctrine\Common\Collections\Collection
    {
        return $this->idOperateur;
    }

    public function addIdOperateur(TrOperateurRelationnel $idOperateur): self
    {
        if (!$this->idOperateur->contains($idOperateur)) {
            $this->idOperateur[] = $idOperateur;
        }

        return $this;
    }

    public function removeIdOperateur(TrOperateurRelationnel $idOperateur): self
    {
        if ($this->idOperateur->contains($idOperateur)) {
            $this->idOperateur->removeElement($idOperateur);
        }

        return $this;
    }

    public function __construct()
    {
        $this->idOperateur = new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getIdCritere(): int
    {
        return $this->idCritere;
    }

    /**
     * @return string
     */
    public function getInputCritere(): string
    {
        return $this->inputCritere;
    }

    /**
     * @param string $inputCritere
     */
    public function setInputCritere(string $inputCritere): void
    {
        $this->inputCritere = $inputCritere;
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

    /**
     * @return string
     */
    public function getLbNomTableValeur(): string
    {
        return $this->lbNomTableValeur;
    }

    /**
     * @param string $lbNomTableValeur
     */
    public function setLbNomTableValeur(string $lbNomTableValeur): void
    {
        $this->lbNomTableValeur = $lbNomTableValeur;
    }

    /**
     * @return string
     */
    public function getTypCritere(): string
    {
        return $this->typCritere;
    }

    /**
     * @param string $typCritere
     */
    public function setTypCritere(string $typCritere): void
    {
        $this->typCritere = $typCritere;
    }

    /**
     * @return string
     */
    public function getTypSearch(): string
    {
        return $this->typSearch;
    }

    /**
     * @param string $typSearch
     */
    public function setTypSearch(string $typSearch): void
    {
        $this->typSearch = $typSearch;
    }

    public function __toString()
    {
        return $this->getLbNomFr();
    }

}
