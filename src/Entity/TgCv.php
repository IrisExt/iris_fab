<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TgCv
 *
 * @ORM\Table(name="tg_cv", uniqueConstraints={@ORM\UniqueConstraint(name="tg_cv_pk", columns={"id_cv"})}, indexes={@ORM\Index(name="IDX_FCD7FB4859DB3928", columns={"id_fonction"})})
 * @ORM\Entity
 */
class TgCv
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cv", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_cv_id_cv_seq", allocationSize=1, initialValue=1)
     */
    private $idCv;

    /**
     * @var TgPersonne
     * @ORM\OneToOne(targetEntity="TgPersonne", mappedBy="idCv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_fonction", type="string", length=50, nullable=true)
     */
    private $lbFonction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_autres_activites", type="text", nullable=true)
     */
    private $lbAutresActivites;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_chercheur", type="string", length=50, nullable=true)
     */
    private $idChercheur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_arret_carriere", type="text", nullable=true)
     */
    private $lbArretCarriere;

    /**
     * @var string|null
     *
     * @ORM\Column(name="diplome_academique", type="text", nullable=true)
     */
    private $diplomeAcademique;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_soutenance_de_these", type="date", nullable=true)
     */
    private $dtSoutenanceDeThese;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_distinction", type="text", nullable=true)
     */
    private $lbDistinction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_valorisation", type="text", nullable=true)
     */
    private $lbValorisation;

    /**
     * @var \TrFonction
     *
     * @ORM\ManyToOne(targetEntity="TrFonction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_fonction", referencedColumnName="id_fonction")
     * })
     */
    private $idFonction;

    /**
     * @var TlCvPubl
     * @ORM\OneToMany(targetEntity="App\Entity\TlCvPubl", mappedBy="idCv", cascade={"persist"})
     */
    private $tlCvPubl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tlCvPubl = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idPersonne = new ArrayCollection();
    }

    public function getIdCv(): ?string
    {
        return $this->idCv;
    }

    public function getLbFonction(): ?string
    {
        return $this->lbFonction;
    }

    public function setLbFonction(?string $lbFonction): self
    {
        $this->lbFonction = $lbFonction;

        return $this;
    }

    public function getLbAutresActivites(): ?string
    {
        return $this->lbAutresActivites;
    }

    public function setLbAutresActivites(?string $lbAutresActivites): self
    {
        $this->lbAutresActivites = $lbAutresActivites;

        return $this;
    }

    public function getIdChercheur(): ?string
    {
        return $this->idChercheur;
    }

    public function setIdChercheur(?string $idChercheur): self
    {
        $this->idChercheur = $idChercheur;

        return $this;
    }

    public function getLbArretCarriere(): ?string
    {
        return $this->lbArretCarriere;
    }

    public function setLbArretCarriere(?string $lbArretCarriere): self
    {
        $this->lbArretCarriere = $lbArretCarriere;

        return $this;
    }

    public function getDiplomeAcademique(): ?string
    {
        return $this->diplomeAcademique;
    }

    public function setDiplomeAcademique(?string $diplomeAcademique): self
    {
        $this->diplomeAcademique = $diplomeAcademique;

        return $this;
    }

    public function getDtSoutenanceDeThese(): ?\DateTimeInterface
    {
        return $this->dtSoutenanceDeThese;
    }

    public function setDtSoutenanceDeThese(?\DateTimeInterface $dtSoutenanceDeThese): self
    {
        $this->dtSoutenanceDeThese = $dtSoutenanceDeThese;

        return $this;
    }

    public function getLbDistinction(): ?string
    {
        return $this->lbDistinction;
    }

    public function setLbDistinction(?string $lbDistinction): self
    {
        $this->lbDistinction = $lbDistinction;

        return $this;
    }

    public function getLbValorisation(): ?string
    {
        return $this->lbValorisation;
    }

    public function setLbValorisation(?string $lbValorisation): self
    {
        $this->lbValorisation = $lbValorisation;

        return $this;
    }

    public function getIdFonction(): ?TrFonction
    {
        return $this->idFonction;
    }

    public function setIdFonction(?TrFonction $idFonction): self
    {
        $this->idFonction = $idFonction;

        return $this;
    }

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    /**
     * @return Collection|TlCvPubl[]
     */
    public function getTlCvPubl(): Collection
    {
        return $this->tlCvPubl;
    }

    public function addTlCvPubl(TlCvPubl $tlCvPubl): self
    {
        if (!$this->tlCvPubl->contains($tlCvPubl)) {
            $this->tlCvPubl[] = $tlCvPubl;
            $tlCvPubl->setIdCv($this);
        }

        return $this;
    }

    public function removeTlCvPubl(TlCvPubl $tlCvPubl): self
    {
        if ($this->tlCvPubl->contains($tlCvPubl)) {
            $this->tlCvPubl->removeElement($tlCvPubl);
            // set the owning side to null (unless already changed)
            if ($tlCvPubl->getIdCv() === $this) {
                $tlCvPubl->setIdCv(null);
            }
        }

        return $this;
    }


}
