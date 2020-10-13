<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrEtatSol
 *
 * @ORM\Table(name="tr_etat_sol", uniqueConstraints={@ORM\UniqueConstraint(name="tr_etat_sol_pk", columns={"cd_etat_sollicitation"})})
 * @ORM\Entity
 */
class TrEtatSol
{
    /**
     * @var int
     *
     * @ORM\Column(name="cd_etat_sollicitation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_etat_sol_cd_etat_sollicitation_seq", allocationSize=1, initialValue=1)
     */
    private $cdEtatSollicitation;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_etat_sollicitation", type="string", length=40, nullable=false)
     */
    private $lbEtatSollicitation;


    public function getCdEtatSollicitation(): ?int
    {
        return $this->cdEtatSollicitation;
    }

    public function getLbEtatSollicitation(): ?string
    {
        return $this->lbEtatSollicitation;
    }

    public function setLbEtatSollicitation(string $lbEtatSollicitation): self
    {
        $this->lbEtatSollicitation = $lbEtatSollicitation;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbEtatSollicitation();
    }


}
