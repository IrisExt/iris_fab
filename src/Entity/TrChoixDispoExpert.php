<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrChoixDispoExpert
 *
 * @ORM\Table(name="tr_choix_dispo_expert", uniqueConstraints={@ORM\UniqueConstraint(name="tr_choix_dispo_expert_pk", columns={"id_choix_expert"})})
 * @ORM\Entity
 */
class TrChoixDispoExpert
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_choix_expert", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_choix_dispo_expert_id_choix_expert_seq", allocationSize=1, initialValue=1)
     */
    private $idChoixExpert;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=false)
     */
    private $lbNomEn;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_ordre", type="smallint", nullable=false, options={"comment"="ordre d'affichage"})
     */
    private $nbOrdre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    public function getIdChoixExpert(): ?string
    {
        return $this->idChoixExpert;
    }

    public function getLbNomEn(): ?string
    {
        return $this->lbNomEn;
    }

    public function setLbNomEn(string $lbNomEn): self
    {
        $this->lbNomEn = $lbNomEn;

        return $this;
    }

    public function getNbOrdre(): ?int
    {
        return $this->nbOrdre;
    }

    public function setNbOrdre(int $nbOrdre): self
    {
        $this->nbOrdre = $nbOrdre;

        return $this;
    }

    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    public function setLbNomFr(string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;

        return $this;
    }

    public function __toString():string
    {
        return $this->getLbNomFr();

    }


}
