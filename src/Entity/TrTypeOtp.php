<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypeOtp
 *
 * @ORM\Table(name="tr_type_otp")
 * @ORM\Entity
 */
class TrTypeOtp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_otp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_otp_id_type_otp_seq", allocationSize=1, initialValue=1)
     */
    private $idTypeOtp;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    public function getIdTypeOtp(): ?int
    {
        return $this->idTypeOtp;
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
