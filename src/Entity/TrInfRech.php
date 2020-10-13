<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrInfRech
 *
 * @ORM\Table(name="tr_inf_rech", uniqueConstraints={@ORM\UniqueConstraint(name="tr_inf_rech_pk", columns={"id_inf_rech"})})
 * @ORM\Entity
 */
class TrInfRech
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_inf_rech", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_inf_rech_id_inf_rech_seq", allocationSize=1, initialValue=1)
     */
    private $idInfRech;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_inf_rech", type="string", length=50, nullable=true)
     */
    private $lbInfRech;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_nom_long", type="text", nullable=true)
     */
    private $lbNomLong;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idInfRech")
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdInfRech(): ?int
    {
        return $this->idInfRech;
    }

    public function getLbInfRech(): ?string
    {
        return $this->lbInfRech;
    }

    public function setLbInfRech(?string $lbInfRech): self
    {
        $this->lbInfRech = $lbInfRech;

        return $this;
    }

    public function getLbNomLong(): ?string
    {
        return $this->lbNomLong;
    }

    public function setLbNomLong(?string $lbNomLong): self
    {
        $this->lbNomLong = $lbNomLong;

        return $this;
    }

    /**
     * @return Collection|TgProjet[]
     */
    public function getIdProjet(): Collection
    {
        return $this->idProjet;
    }

    public function addIdProjet(TgProjet $idProjet): self
    {
        if (!$this->idProjet->contains($idProjet)) {
            $this->idProjet[] = $idProjet;
            $idProjet->addIdInfRech($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdInfRech($this);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->getLbInfRech();
    }

}
