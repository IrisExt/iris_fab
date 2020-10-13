<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrAvisProjet
 *
 * @ORM\Table(name="tr_avis_projet", uniqueConstraints={@ORM\UniqueConstraint(name="cd_avis_projet_pk", columns={"cd_avis"})})
 * @ORM\Entity
 */
class TrAvisProjet
{

    /**
     * @var int
     *
     * @ORM\Column(name="cd_avis", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_avis_projet_cd_avis_seq", allocationSize=1, initialValue=1)
     */
    private $cdAvis;

    /**
     * @var string
     *
     * @ORM\Column(name="code_avis", type="string", length=3, nullable=false)
     */
    private $codeAvis;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=false)
     */
    private $lbNomEn;

    /**
     * @var string
     *
     * @ORM\Column(name="cd_couleur", type="string", length=10, nullable=true)
     */
    private $cdCouleur;

    /**
     * @var TlAvisProjet
     * @ORM\OneToMany(targetEntity="TlAvisProjet", mappedBy="cdAvis", cascade={"persist"})
     */
    private $avisProjet;

    /**
     * @var TlAvisPossibles
     * @ORM\OneToMany(targetEntity="TlAvisPossibles", mappedBy="cdAvis", cascade={"persist"})
     */
    private $avisPossibles;

    public function __construct()
    {
        $this->avisProjet = new ArrayCollection();
        $this->avisPossibles = new ArrayCollection();
    }

    public function getCdAvis(): ?string
    {
        return $this->cdAvis;
    }

    public function getCodeAvis(): ?string
    {
        return $this->codeAvis;
    }

    public function setCodeAvis(string $codeAvis): self
    {
        $this->codeAvis = $codeAvis;

        return $this;
    }

    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    public function setLbNomFr(string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;

        return $this;
    }

    public function getLbNomEn(): ?string
    {
        return $this->lbNomEn;
    }

    public function setLbNomEn(string $lbNomEn): self
    {
        $this->lbNomEn = $lbNomEn;

        return $this;
    }

    public function getCdCouleur(): ?string
    {
        return $this->cdCouleur;
    }

    public function setCdCouleur(?string $cdCouleur): self
    {
        $this->cdCouleur = $cdCouleur;

        return $this;
    }

    /**
     * @return Collection|TlAvisProjet[]
     */
    public function getAvisProjet(): Collection
    {
        return $this->avisProjet;
    }

    public function addAvisProjet(TlAvisProjet $avisProjet): self
    {
        if (!$this->avisProjet->contains($avisProjet)) {
            $this->avisProjet[] = $avisProjet;
            $avisProjet->setCdAvis($this);
        }

        return $this;
    }

    public function removeAvisProjet(TlAvisProjet $avisProjet): self
    {
        if ($this->avisProjet->contains($avisProjet)) {
            $this->avisProjet->removeElement($avisProjet);
            // set the owning side to null (unless already changed)
            if ($avisProjet->getCdAvis() === $this) {
                $avisProjet->setCdAvis(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlAvisPossibles[]
     */
    public function getAvisPossibles(): Collection
    {
        return $this->avisPossibles;
    }

    public function addAvisPossible(TlAvisPossibles $avisPossible): self
    {
        if (!$this->avisPossibles->contains($avisPossible)) {
            $this->avisPossibles[] = $avisPossible;
            $avisPossible->setCdAvis($this);
        }

        return $this;
    }

    public function removeAvisPossible(TlAvisPossibles $avisPossible): self
    {
        if ($this->avisPossibles->contains($avisPossible)) {
            $this->avisPossibles->removeElement($avisPossible);
            // set the owning side to null (unless already changed)
            if ($avisPossible->getCdAvis() === $this) {
                $avisPossible->setCdAvis(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getLbNomFr();
    }


}