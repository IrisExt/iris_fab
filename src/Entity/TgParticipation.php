<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgParticipation
 *
 * @ORM\Table(name="tg_participation", uniqueConstraints={@ORM\UniqueConstraint(name="tg_participation_pk", columns={"id_participation"})})
 * @ORM\Entity(repositoryClass="App\Repository\ParticipationRepository")
 */
class TgParticipation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_participation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_participation_id_participation_seq", allocationSize=1, initialValue=1)
     */
    private $idParticipation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_groupe", type="string", length=400, nullable=true)
     * @Assert\Length(
     *      max = 400,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbGroupe;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prio_grp", type="smallint", nullable=true)
     */
    private $prioGrp;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_supprime", type="smallint", nullable=true, options={"comment"="booleen qui indique que la participation de la personne au comité a été supprimée"})
     */
    private $blSupprime;

    /**
     * @var \TrEtatSol
     *
     * @ORM\ManyToOne(targetEntity="TrEtatSol", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_etat_sollicitation", referencedColumnName="cd_etat_sollicitation")
     * })
     */
    private $cdEtatSollicitation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgMessage", mappedBy="idParticipation", cascade={"persist", "remove"})
     */
    private $idMessage;

    /**
     * @var TrPhase
     *
     * @ORM\ManyToOne(targetEntity="TrPhase", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase_ref", referencedColumnName="id_phase_ref")
     * })
     */
    private $idPhaseRef;

    /**
     * @var \TgComite
     *
     * @ORM\ManyToOne(targetEntity="TgComite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     */
    private $idComite;

    /**
 * @var \TgPersonne
 *
 * @ORM\ManyToOne(targetEntity="TgPersonne")
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
     * @var bool
     *
     * @ORM\Column(name="quest_soum", type="boolean", nullable=true, options={"default":false})
     *
     */

    private $questSoum;


    public function __construct()
    {
        $this->idMessage = new ArrayCollection();
    }

    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getLbGroupe(): ?string
    {
        return $this->lbGroupe;
    }

    public function setLbGroupe(?string $lbGroupe): self
    {
        $this->lbGroupe = $lbGroupe;

        return $this;
    }

    public function getPrioGrp(): ?int
    {
        return $this->prioGrp;
    }

    public function setPrioGrp(?int $prioGrp): self
    {
        $this->prioGrp = $prioGrp;

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

    public function getCdEtatSollicitation(): ?TrEtatSol
    {
        return $this->cdEtatSollicitation;
    }

    public function setCdEtatSollicitation(?TrEtatSol $cdEtatSollicitation): self
    {
        $this->cdEtatSollicitation = $cdEtatSollicitation;

        return $this;
    }

    /**
     * @return Collection|TgMessage[]
     */
    public function getIdMessage(): Collection
    {
        return $this->idMessage;
    }

    public function addIdMessage(TgMessage $idMessage): self
    {
        if (!$this->idMessage->contains($idMessage)) {
            $this->idMessage[] = $idMessage;
            $idMessage->setIdParticipation($this);
        }

        return $this;
    }

    public function removeIdMessage(TgMessage $idMessage): self
    {
        if ($this->idMessage->contains($idMessage)) {
            $this->idMessage->removeElement($idMessage);
            // set the owning side to null (unless already changed)
            if ($idMessage->getIdParticipation() === $this) {
                $idMessage->setIdParticipation(null);
            }
        }

        return $this;
    }

    public function getIdPhaseRef(): ?TrPhase
    {
        return $this->idPhaseRef;
    }

    public function setIdPhaseRef(?TrPhase $idPhaseRef): self
    {
        $this->idPhaseRef = $idPhaseRef;

        return $this;
    }

    public function getIdComite(): ?TgComite
    {
        return $this->idComite;
    }

    public function setIdComite(?TgComite $idComite): self
    {
        $this->idComite = $idComite;

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

    public function __toString()
    {
        return $this->getIdComite()->getLbAcr();
    }

    /**
     * @return bool
     */
    public function isQuestSoum(): bool
    {
        return $this->questSoum;
    }

    /**
     * @param bool $questSoum
     */
    public function setQuestSoum(bool $questSoum): void
    {
        $this->questSoum = $questSoum;
    }

    public function getQuestSoum(): ?bool
    {
        return $this->questSoum;
    }


}
