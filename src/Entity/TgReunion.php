<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgReunion
 *
 * @ORM\Table(name="tg_reunion", uniqueConstraints={@ORM\UniqueConstraint(name="tg_reunion_pk", columns={"id_reunion"})}, indexes={@ORM\Index(name="a_pour_type_reunion_fk", columns={"id_type_reunion"}), @ORM\Index(name="reunion_prevue_pour_phase_fk", columns={"id_phase"}), @ORM\Index(name="tl_com_reu_fk", columns={"id_appel"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReunionRepository")
 */
class TgReunion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reunion", type="bigint", nullable=false, options={"comment"="identifiant de la réunion du comité"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_reunion_id_reunion_seq", allocationSize=1, initialValue=1)
     */
    private $idReunion;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_titre", type="string", length=50, nullable=false, options={"comment"="Titre du comité"})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbTitre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tx_comment", type="text", nullable=true)
     */
    private $txComment;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_deb_periode", type="date", nullable=true)
     */
    private $dtDebPeriode;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_fin_periode", type="date", nullable=true, options={"comment"="Date de fin de période pour programmer les réunions"})
     */
    private $dtFinPeriode;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_duree_max", type="integer", nullable=true)
     */
    private $nbDureeMax;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_obligatoire", type="boolean", nullable=true)
     */
    private $blObligatoire;

    /**
     * @var int
     *
     * @ORM\Column(name="bl_actif", type="bigint", nullable=false, options={"comment"="Précise si la reunion est actif ou supprimé (valeur 0)"})
     */
    private $blActif;

    /**
     * @var \TrTypeReunion
     *
     * @ORM\ManyToOne(targetEntity="TrTypeReunion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_reunion", referencedColumnName="id_type_reunion")
     * })
     */
    private $idTypeReunion;

    /**
     * @var TgPhase
     *
     * @ORM\ManyToOne(targetEntity="TgPhase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase", referencedColumnName="id_phase")
     * })
     */
    private $idPhase;

    /**
     * @var TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj", inversedBy="idReunion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgSeance", mappedBy="idReunion", cascade={"persist"})
     * OrderBy("dt_seance" = "ASC")
     */
    private $idSeance;

    public function __construct()
    {
        $this->idSeance = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getIdPhase()->getIdPhaseRef()->getLbNom(). ' : ' . $this->getLbTitre();
    }

    public function getIdReunion(): ?int
    {
        return $this->idReunion;
    }

    public function getLbTitre(): ?string
    {
        return $this->lbTitre;
    }

    public function setLbTitre(string $lbTitre): self
    {
        $this->lbTitre = $lbTitre;

        return $this;
    }

    public function getTxComment(): ?string
    {
        return $this->txComment;
    }

    public function setTxComment(?string $txComment): self
    {
        $this->txComment = $txComment;

        return $this;
    }

    public function getDtDebPeriode(): ?\DateTimeInterface
    {
        return $this->dtDebPeriode;
    }

    public function setDtDebPeriode(?\DateTimeInterface $dtDebPeriode): self
    {
        $this->dtDebPeriode = $dtDebPeriode;

        return $this;
    }

    public function getDtFinPeriode(): ?\DateTimeInterface
    {
        return $this->dtFinPeriode;
    }

    public function setDtFinPeriode(?\DateTimeInterface $dtFinPeriode): self
    {
        $this->dtFinPeriode = $dtFinPeriode;

        return $this;
    }

    public function getNbDureeMax(): ?int
    {
        return $this->nbDureeMax;
    }

    public function setNbDureeMax(?int $nbDureeMax): self
    {
        $this->nbDureeMax = $nbDureeMax;

        return $this;
    }

    public function getBlObligatoire(): ?bool
    {
        return $this->blObligatoire;
    }

    public function setBlObligatoire(?bool $blObligatoire): self
    {
        $this->blObligatoire = $blObligatoire;

        return $this;
    }

    public function getBlActif(): ?int
    {
        return $this->blActif;
    }

    public function setBlActif(int $blActif): self
    {
        $this->blActif = $blActif;

        return $this;
    }

    public function getIdTypeReunion(): ?TrTypeReunion
    {
        return $this->idTypeReunion;
    }

    public function setIdTypeReunion(?TrTypeReunion $idTypeReunion): self
    {
        $this->idTypeReunion = $idTypeReunion;

        return $this;
    }



    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    /**
     * @return Collection|TgSeance[]
     */
    public function getIdSeance(): Collection
    {
        return $this->idSeance;
    }

    public function addIdSeance(TgSeance $idSeance): self
    {
        if (!$this->idSeance->contains($idSeance)) {
            $this->idSeance[] = $idSeance;
            $idSeance->setIdReunion($this);
        }

        return $this;
    }

    public function removeIdSeance(TgSeance $idSeance): self
    {
        if ($this->idSeance->contains($idSeance)) {
            $this->idSeance->removeElement($idSeance);
            // set the owning side to null (unless already changed)
            if ($idSeance->getIdReunion() === $this) {
                $idSeance->setIdReunion(null);
            }
        }

        return $this;
    }

    public function getIdPhase(): ?TgPhase
    {
        return $this->idPhase;
    }

    public function setIdPhase(?TgPhase $idPhase): self
    {
        $this->idPhase = $idPhase;

        return $this;
    }

}
