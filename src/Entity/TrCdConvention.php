<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCdConvention
 *
 * @ORM\Table(name="tr_cd_convention")
 * @ORM\Entity
 */
class TrCdConvention
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cd_convention", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_cd_convention_id_cd_convention_seq", allocationSize=1, initialValue=1)
     */
    private $idCdConvention;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    public function getIdCdConvention(): ?int
    {
        return $this->idCdConvention;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }


}
