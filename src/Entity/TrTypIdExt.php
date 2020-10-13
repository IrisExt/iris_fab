<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypIdExt
 *
 * @ORM\Table(name="tr_typ_id_ext", uniqueConstraints={@ORM\UniqueConstraint(name="tr_typ_id_ext_pk", columns={"id_type_ref_ext"})})
 * @ORM\Entity
 */
class TrTypIdExt
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_type_ref_ext", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_typ_id_ext_id_type_ref_ext_seq", allocationSize=1, initialValue=1)
     */
    private $idTypeRefExt;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;


    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    public function setLbNomFr(string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;

        return $this;
    }

    public function getIdTypeRefExt(): ?string
    {
        return $this->idTypeRefExt;
    }


}
