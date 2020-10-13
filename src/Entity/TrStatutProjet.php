<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrStatutProjet
 *
 * @ORM\Table(name="tr_statut_projet")
 * @ORM\Entity
 */
class TrStatutProjet
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_sts_projet", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_statut_projet_id_sts_projet_seq", allocationSize=1, initialValue=1)
     */
    private $idStsProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    public function getIdStsProjet(): ?string
    {
        return $this->idStsProjet;
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
