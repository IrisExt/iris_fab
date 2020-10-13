<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrInstFi
 *
 * @ORM\Table(name="tr_inst_fi", uniqueConstraints={@ORM\UniqueConstraint(name="tr_inst_fi_pk", columns={"id_inst_fi"})})
 * @ORM\Entity
 */
class TrInstFi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_inst_fi", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_inst_fi_id_inst_fi_seq", allocationSize=1, initialValue=1)
     */
    private $idInstFi;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_acro", type="string", length=10, nullable=true)
     */
    private $lbAcro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_max", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntMax;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_min", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntMin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgAppelProj", mappedBy="idInstFi")
     */
    private $idAppel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAppel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdInstFi(): ?int
    {
        return $this->idInstFi;
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

    public function getMntMax()
    {
        return $this->mntMax;
    }

    public function setMntMax($mntMax): self
    {
        $this->mntMax = $mntMax;

        return $this;
    }

    public function getMntMin()
    {
        return $this->mntMin;
    }

    public function setMntMin($mntMin): self
    {
        $this->mntMin = $mntMin;

        return $this;
    }

    /**
     * @return Collection|TgAppelProj[]
     */
    public function getIdAppel(): Collection
    {
        return $this->idAppel;
    }

    public function addIdAppel(TgAppelProj $idAppel): self
    {
        if (!$this->idAppel->contains($idAppel)) {
            $this->idAppel[] = $idAppel;
            $idAppel->addIdInstFi($this);
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
            $idAppel->removeIdInstFi($this);
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getLbAcro(): string
    {
        return $this->lbAcro;
    }

    /**
     * @param string $lbAcro
     */
    public function setLbAcro(string $lbAcro): void
    {
        $this->lbAcro = $lbAcro;
    }

    public function __toString() :string
    {
        return $this->getLbNom();
    }
}
