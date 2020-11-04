<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TgHabilitation.
 *
 * @ORM\Table(name="tg_habilitation", uniqueConstraints={@ORM\UniqueConstraint(name="tg_habilitation_pk", columns={"id_habilitation"})}, indexes={@ORM\Index(name="association_98_fk", columns={"id_personne"})})
 * @ORM\Entity(repositoryClass="App\Repository\HabilitationRepository")
 */
class TgHabilitation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_habilitation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_habilitation_id_habilitation_seq", allocationSize=1, initialValue=1)
     */
    private $idHabilitation;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="dh_maj", type="date", nullable=true)
     */
    private $dhMaj;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_resp_maj", type="string", length=50, nullable=true)
     */
    private $lbRespMaj;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_supprime", type="smallint", nullable=true,  options={"default": 1 })
     */
    private $blSupprime = 1;

    /**
     * @var TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne" , inversedBy="idHabilitation", cascade={"remove"} )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var TrProfil
     *
     * @ORM\ManyToOne(targetEntity="TrProfil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil")
     * })
     */
    private $idProfil;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="TgComite", mappedBy="idHabilitation", cascade={"remove"})
     */
    private $idComite;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="TgPhase", mappedBy="idHabilitation", cascade={"remove"})
     */
    private $idPhase;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", inversedBy="idHabilitation")
     * @ORM\JoinTable(name="tl_hab_projet",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_habilitation", referencedColumnName="id_habilitation")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   }
     * )
     */
    private $idProjet;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="TgAppelProj", mappedBy="idHabilitation")
     */
    private $idAppel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dhMaj = new DateTime('now');
        $this->idComite = new ArrayCollection();
        $this->idPhase = new ArrayCollection();
        $this->idProjet = new ArrayCollection();
        $this->idAppel = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getIdProfil();
    }

    public function getIdHabilitation(): ?int
    {
        return $this->idHabilitation;
    }

    public function getDhMaj(): ?DateTimeInterface
    {
        return $this->dhMaj;
    }

    public function setDhMaj(?DateTimeInterface $dhMaj): self
    {
        $this->dhMaj = $dhMaj;

        return $this;
    }

    public function getBlSupprime(): ?int
    {
        return $this->blSupprime;
    }

    public function setBlSupprime(?int $blSupprime): self
    {
        $this->blSupprime = $blSupprime;

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

    public function getIdProfil(): ?TrProfil
    {
        return $this->idProfil;
    }

    public function setIdProfil(?TrProfil $idProfil): self
    {
        $this->idProfil = $idProfil;

        return $this;
    }

    /**
     * @return Collection|TgComite[]
     */
    public function getIdComite(): Collection
    {
        return $this->idComite;
    }

    /**
     * @param TgComite $idComite
     * @return $this
     */
    public function addIdComite(TgComite $idComite): self
    {
        if (!$this->idComite->contains($idComite)) {
            $this->idComite[] = $idComite;
            $idComite->addIdHabilitation($this);
        }

        return $this;
    }

    public function removeIdComite(TgComite $idComite): self
    {
        if ($this->idComite->contains($idComite)) {
            $this->idComite->removeElement($idComite);
            $idComite->removeIdHabilitation($this);
        }

        return $this;
    }

    /**
     * @return Collection|TgPhase[]
     */
    public function getIdPhase(): Collection
    {
        return $this->idPhase;
    }

    public function addIdPhase(TgPhase $idPhase): self
    {
        if (!$this->idPhase->contains($idPhase)) {
            $this->idPhase[] = $idPhase;
            $idPhase->addIdHabilitation($this);
        }

        return $this;
    }

    public function removeIdPhase(TgPhase $idPhase): self
    {
        if ($this->idPhase->contains($idPhase)) {
            $this->idPhase->removeElement($idPhase);
            $idPhase->removeIdHabilitation($this);
        }

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
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
        }

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
            $idAppel->addIdHabilitation($this);
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
            $idAppel->removeIdHabilitation($this);
        }

        return $this;
    }

    public function getAllPhase(): array
    {
        foreach ($this->getIdPhase() as $PhaseColl) {
            $phase[] = $PhaseColl;
        }

        return $phase;
    }

    public function getAllComite(): array
    {
        foreach ($this->getIdComite() as $ComiteColl) {
            $comite[] = $ComiteColl;
        }

        return $comite;
    }

    public function getAllAppel(): array
    {
        foreach ($this->getIdAppel() as $AppelColl) {
            $appel[] = $AppelColl;
        }

        return $appel;
    }

    public function getLbRespMaj(): ?string
    {
        return $this->lbRespMaj;
    }

    public function setLbRespMaj(?string $lbRespMaj): self
    {
        $this->lbRespMaj = $lbRespMaj;

        return $this;
    }
}
