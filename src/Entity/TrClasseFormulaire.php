<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrClasseFormulaire
 *
 * @ORM\Table(name="tr_classe_formulaire", uniqueConstraints={@ORM\UniqueConstraint(name="tr_classe_formulaire_pk", columns={"id_classe_formulaire"})})
 * @ORM\Entity
 */
class TrClasseFormulaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_classe_formulaire", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_classe_formulaire_id_classe_formulaire_seq", allocationSize=1, initialValue=1)
     */
    private $idClasseFormulaire;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgAppelProj", mappedBy="idClasseFormulaire")
     */
    private $idAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrProfil", inversedBy="idClasseFormulaire")
     * @ORM\JoinTable(name="tl_profil_classe",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_classe_formulaire", referencedColumnName="id_classe_formulaire")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil")
     *   }
     * )
     */
    private $idProfil;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAppel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idProfil = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdClasseFormulaire(): ?int
    {
        return $this->idClasseFormulaire;
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
            $idAppel->addIdClasseFormulaire($this);
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
            $idAppel->removeIdClasseFormulaire($this);
        }

        return $this;
    }

    /**
     * @return Collection|TrProfil[]
     */
    public function getIdProfil(): Collection
    {
        return $this->idProfil;
    }

    public function addIdProfil(TrProfil $idProfil): self
    {
        if (!$this->idProfil->contains($idProfil)) {
            $this->idProfil[] = $idProfil;
        }

        return $this;
    }

    public function removeIdProfil(TrProfil $idProfil): self
    {
        if ($this->idProfil->contains($idProfil)) {
            $this->idProfil->removeElement($idProfil);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbNom();
    }

}
