<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrPays
 *
 * @ORM\Table(name="tr_pays", uniqueConstraints={@ORM\UniqueConstraint(name="tr_pays_pk", columns={"cd_pays"})})
 * @ORM\Entity
 */
class TrPays
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_pays", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_pays_cd_pays_seq", allocationSize=1, initialValue=1)
     */
    private $cdPays;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_pays", type="string", length=100, nullable=false)
     */
    private $lbPays;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_pays_en", type="string", length=100, nullable=false)
     */
    private $lbPaysEn;

    /**
     * @var string
     *
     * @ORM\Column(name="alpha2", type="string", length=2, nullable=false)
     */
    private $alpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="alpha3", type="string", length=3, nullable=false)
     */
    private $alpha3;

    public function getCdPays(): ?string
    {
        return $this->cdPays;
    }

    public function setCdPays(string $cdPays): self
    {
        $this->cdPays = $cdPays;

        return $this;
    }

    public function getLbPays(): ?string
    {
        return $this->lbPays;
    }

    public function setLbPays(string $lbPays): self
    {
        $this->lbPays = $lbPays;

        return $this;
    }

    public function getLbPaysEn(): ?string
    {
        return $this->lbPaysEn;
    }

    public function setLbPaysEn(string $lbPaysEn): self
    {
        $this->lbPaysEn = $lbPaysEn;

        return $this;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): self
    {
        $this->alpha3 = $alpha3;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbPays();
    }


}
