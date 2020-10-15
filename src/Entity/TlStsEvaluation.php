<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TlStsEvaluationRepository")
 * @ORM\Table(name="tl_sts_evaluation")
 */
class TlStsEvaluation
{

    /**
     * @var int
     *
     * @ORM\Column(name="id_sts_evaluation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tl_sts_evaluation_id_sts_evaluation_seq", allocationSize=1, initialValue=1)
     */
    private $idStsEvaluation;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TrStsEvaluation", inversedBy="tlStsEvaluation", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_sts_evaluation", referencedColumnName="cd_sts_evaluation")
     * })
     */
    private $cdStsEvaluation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TrStsSollicitation", inversedBy="tlStsEvaluation", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_sollicitation", referencedColumnName="cd_sollicitation")
     * })
     */
    private $cdSollicitation;

    /**
     * @ORM\Column(name="color_hexa", type="string", length=6)
     */
    private $colorHexa;

    /**
     * @ORM\Column(name="ordre", type="smallint")
     */
    private $ordre;

    public function getIdStsEvaluation() 
    {
        return $this->idStsEvaluation;
    }

    public function getCdStsEvaluation(): ?TrStsEvaluation
    {
        return $this->cdStsEvaluation;
    }

    public function setCdStsEvaluation(?TrStsEvaluation $cdStsEvaluation): self
    {
        $this->cdStsEvaluation = $cdStsEvaluation;

        return $this;
    }

    public function getCdSollicitation(): ?TrStsSollicitation
    {
        return $this->cdSollicitation;
    }

    public function setCdSollicitation(?TrStsSollicitation $cdSollicitation): self
    {
        $this->cdSollicitation = $cdSollicitation;

        return $this;
    }

    public function getColorHexa(): ?string
    {
        return $this->colorHexa;
    }

    public function setColorHexa(string $colorHexa): self
    {
        $this->colorHexa = $colorHexa;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
