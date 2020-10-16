<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrStsEvaluation
 *
 * @ORM\Table(name="tr_sts_evaluation", uniqueConstraints={@ORM\UniqueConstraint(name="tr_sts_evaluation_pk", columns={"cd_sts_evaluation"})})
 * @ORM\Entity
 */
class TrStsEvaluation
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_sts_evaluation", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_sts_evaluation_cd_sts_evaluation_seq", allocationSize=1, initialValue=1)
     */
    private $cdStsEvaluation;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_description", type="string", length=50, nullable=false, options={"comment"="Explicitation de la r�le (affichable)"})
     */
    private $lbDescription;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TlStsEvaluation", mappedBy="cdStsEvaluation", cascade={"persist", "remove"})
     */
    private $tlStsEvaluation;
    
    public function getCdStsEvaluation(): ?string
    {
        return $this->cdStsEvaluation;
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

    public function getTlStsEvaluation(): ?TlStsEvaluation
    {
        return $this->tlStsEvaluation;
    }

    public function setTlStsEvaluation(?TlStsEvaluation $tlStsEvaluation): self
    {
        $this->tlStsEvaluation = $tlStsEvaluation;

        // set (or unset) the owning side of the relation if necessary
        $newCdStsEvaluation = null === $tlStsEvaluation ? null : $this;
        if ($tlStsEvaluation->getCdStsEvaluation() !== $newCdStsEvaluation) {
            $tlStsEvaluation->setCdStsEvaluation($newCdStsEvaluation);
        }

        return $this;
    }


}
