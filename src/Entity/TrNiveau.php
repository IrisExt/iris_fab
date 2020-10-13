<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrNiveau
 *
 * @ORM\Table(name="tr_niveau", uniqueConstraints={@ORM\UniqueConstraint(name="tr_niveau_pk", columns={"id_type_niveu"})})
 * @ORM\Entity
 */
class TrNiveau
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_niveu", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_niveau_id_type_niveu_seq", allocationSize=1, initialValue=1)
     */
    private $idTypeNiveu;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    public function getIdTypeNiveu(): ?int
    {
        return $this->idTypeNiveu;
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
    public function __toString() : string
    {
        return $this->getLbNom();
    }


}
