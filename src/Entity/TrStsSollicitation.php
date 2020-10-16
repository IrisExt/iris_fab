<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrStsSollicitation
 *
 * @ORM\Table(name="tr_sts_sollicitation", uniqueConstraints={@ORM\UniqueConstraint(name="tr_type_sollicitation_pk", columns={"cd_sollicitation"})})
 * @ORM\Entity
 */
class TrStsSollicitation
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_sollicitation", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_sts_sollicitation_cd_sollicitation_seq", allocationSize=1, initialValue=1)
     */
    private $cdSollicitation;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_description", type="string", length=50, nullable=false, options={"comment"="Explicitation de la r�le (affichable)"})
     */
    private $lbDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="action_sollicitation", type="string", length=50, nullable=true, options={"comment"="Explicitation de la r�le (affichable)"})
     */
    private $actionSollicitation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TlStsEvaluation", mappedBy="cdSollicitation", cascade={"persist", "remove"})
     */
    private $tlStsEvaluation;

    public function getCdSollicitation(): ?string
    {
        return $this->cdSollicitation;
    }

    /**
     * @return string
     */
    public function getLbDescription(): string
    {
        return $this->lbDescription;
    }

    /**
     * @param string $lbDescription
     */
    public function setLbDescription(string $lbDescription): void
    {
        $this->lbDescription = $lbDescription;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getLbDescription();
    }

    /**
     * @return string
     */
    public function getActionSollicitation(): string
    {
        return $this->actionSollicitation;
    }

    /**
     * @param string $actionSollicitation
     */
    public function setActionSollicitation(string $actionSollicitation): void
    {
        $this->actionSollicitation = $actionSollicitation;
    }

    public function getTlStsEvaluation(): ?TlStsEvaluation
    {
        return $this->tlStsEvaluation;
    }

    public function setTlStsEvaluation(?TlStsEvaluation $tlStsEvaluation): self
    {
        $this->tlStsEvaluation = $tlStsEvaluation;

        // set (or unset) the owning side of the relation if necessary
        $newCdSollicitation = null === $tlStsEvaluation ? null : $this;
        if ($tlStsEvaluation->getCdSollicitation() !== $newCdSollicitation) {
            $tlStsEvaluation->setCdSollicitation($newCdSollicitation);
        }

        return $this;
    }

}
