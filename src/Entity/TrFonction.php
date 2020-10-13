<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrFonction
 *
 * @ORM\Table(name="tr_fonction", uniqueConstraints={@ORM\UniqueConstraint(name="tr_fonction_pk", columns={"id_fonction"})})
 * @ORM\Entity
 */
class TrFonction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_fonction", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_fonction_id_fonction_seq", allocationSize=1, initialValue=1)
     */
    private $idFonction;

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

    public function getIdFonction(): ?string
    {
        return $this->idFonction;
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

    public function getLbNomEn(): ?string
    {
        return $this->lbNomEn;
    }

    public function setLbNomEn(string $lbNomEn): self
    {
        $this->lbNomEn = $lbNomEn;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbNomFr();
    }


}
