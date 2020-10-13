<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrAgFi
 *
 * @ORM\Table(name="tr_ag_fi", uniqueConstraints={@ORM\UniqueConstraint(name="agence_de_financement_pk", columns={"id_agence_fi"})})
 * @ORM\Entity(repositoryClass="App\Repository\TrAgFiRepository")
 */
class TrAgFi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_agence_fi", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_ag_fi_id_agence_fi_seq", allocationSize=1, initialValue=1)
     */
    private $idAgenceFi;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_agenc_fi", type="string", length=50, nullable=false)
     */
    private $lbAgencFi;

    public function getIdAgenceFi(): ?int
    {
        return $this->idAgenceFi;
    }

    public function getLbAgencFi(): ?string
    {
        return $this->lbAgencFi;
    }

    public function setLbAgencFi(string $lbAgencFi): self
    {
        $this->lbAgencFi = $lbAgencFi;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbAgencFi();
    }

}
