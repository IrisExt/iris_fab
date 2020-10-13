<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrPolComp
 *
 * @ORM\Table(name="tr_pol_comp", uniqueConstraints={@ORM\UniqueConstraint(name="tr_pol_comp_pk", columns={"id_pole_comp"})})
 * @ORM\Entity
 */
class TrPolComp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pole_comp", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_pol_comp_id_pole_comp_seq", allocationSize=1, initialValue=1)
     */
    private $idPoleComp;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_pol_comp", type="string", length=40, nullable=false)
     */
    private $lbPolComp;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idPoleComp")
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdPoleComp(): ?int
    {
        return $this->idPoleComp;
    }

    public function getLbPolComp(): ?string
    {
        return $this->lbPolComp;
    }

    public function setLbPolComp(string $lbPolComp): self
    {
        $this->lbPolComp = $lbPolComp;

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
            $idProjet->addIdPoleComp($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdPoleComp($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getLbPolComp();
    }

}
