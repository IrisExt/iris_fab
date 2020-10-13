<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypePart
 *
 * @ORM\Table(name="tr_type_part")
 * @ORM\Entity
 */
class TrTypePart
{
    /**
     * @var string
     *
     * @ORM\Column(name="typ_part", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_part_typ_part_seq", allocationSize=1, initialValue=1)
     */
    private $typPart;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    public function getTypPart(): ?string
    {
        return $this->typPart;
    }

    public function setTypPart(string $typPart): self
    {
        $this->typPart = $typPart;

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


}
