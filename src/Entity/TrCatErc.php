<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCatErc
 *
 * @ORM\Table(name="tr_cat_erc")
 * @ORM\Entity
 */
class TrCatErc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_erc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_cat_erc_id_cat_erc_seq", allocationSize=1, initialValue=1)
     */
    private $idCatErc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    public function getIdCatErc(): ?int
    {
        return $this->idCatErc;
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


}
