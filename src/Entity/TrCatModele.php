<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCatModele
 *
 * @ORM\Table(name="tr_cat_modele", uniqueConstraints={@ORM\UniqueConstraint(name="tr_cat_courriel_pk", columns={"id_cat_modele"})})
 * @ORM\Entity
 */
class TrCatModele
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_modele", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_cat_modele_id_cat_modele_seq", allocationSize=1, initialValue=1)
     */
    private $idCatModele;

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
     * @return int
     */
    public function getIdCatModele(): int
    {
        return $this->idCatModele;
    }

    /**
     * @param int $idCatModele
     */
    public function setIdCatModele(int $idCatModele): void
    {
        $this->idCatModele = $idCatModele;
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

    public function __toString(): string
    {
        return $this->getLbNomFr();
    }

}
