<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrCoFi
 *
 * @ORM\Table(name="tr_co_fi", uniqueConstraints={@ORM\UniqueConstraint(name="tr_co_fi_pk", columns={"id_co_fi"})})
 * @ORM\Entity
 */
class TrCoFi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_co_fi", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_co_fi_id_co_fi_seq", allocationSize=1, initialValue=1)
     */
    private $idCoFi;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_co_fi", type="string", length=255, nullable=true)
     */
    private $lbCoFi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idCoFi")
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdCoFi(): ?int
    {
        return $this->idCoFi;
    }

    public function getLbCoFi(): ?string
    {
        return $this->lbCoFi;
    }

    public function setLbCoFi(?string $lbCoFi): self
    {
        $this->lbCoFi = $lbCoFi;

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
            $idProjet->addIdCoFi($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdCoFi($this);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->getLbCoFi();
    }
}
