<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrInfo
 *
 * @ORM\Table(name="tr_info", uniqueConstraints={@ORM\UniqueConstraint(name="tr_info_pk", columns={"cd_info"})})
 * @ORM\Entity
 */
class TrInfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_info", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_info_cd_info_seq", allocationSize=1, initialValue=1)
     */
    private $cdInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_info", type="text", nullable=false)
     */
    private $lbInfo;

    public function getCdInfo(): ?string
    {
        return $this->cdInfo;
    }

    public function setCdInfo(string $cdInfo): self
    {
        $this->cdInfo = $cdInfo;

        return $this;
    }

    public function getLbInfo(): ?string
    {
        return $this->lbInfo;
    }

    public function setLbInfo(string $lbInfo): self
    {
        $this->lbInfo = $lbInfo;

        return $this;
    }


}
