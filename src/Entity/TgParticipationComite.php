<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TgParticipationComite
 *
 * @ORM\Table(name="tg_participation_comite", uniqueConstraints={@ORM\UniqueConstraint(name="tg_participation_comite_pk", columns={"id_participation"})}, indexes={@ORM\Index(name="participe_fk", columns={"id_personne"}), @ORM\Index(name="participe_durant_la_phase_fk", columns={"id_phase_ref"}), @ORM\Index(name="est_en_etat_sollicitation_fk", columns={"cd_etat_sollicitation"}), @ORM\Index(name="contient_part_fk", columns={"id_comite"})})
 * @ORM\Entity
 */
class TgParticipationComite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_participation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_participation_comite_id_participation_seq", allocationSize=1, initialValue=1)
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_maj", type="date", nullable=true)
     */
    private $dhMaj;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_resp_maj", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbRespMaj;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_supprime", type="smallint", nullable=true, options={"comment"="booleen qui indique que la participation de la personne au comité a été supprimée"})
     */
    private $blSupprime;

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
     * @var \TrEtatSol
     *
     * @ORM\ManyToOne(targetEntity="TrEtatSol")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_etat_sollicitation", referencedColumnName="cd_etat_sollicitation")
     * })
     */
    private $cdEtatSollicitation;

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
     * @var \TrPhase
     *
     * @ORM\ManyToOne(targetEntity="TrPhase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase_ref", referencedColumnName="id_phase_ref")
     * })
     */
    private $idPhaseRef;

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

    public function getDhMaj(): ?\DateTimeInterface
    {
        return $this->dhMaj;
    }

    public function setDhMaj(?\DateTimeInterface $dhMaj): self
    {
        $this->dhMaj = $dhMaj;

        return $this;
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

    public function getBlSupprime(): ?int
    {
        return $this->blSupprime;
    }

    public function setBlSupprime(?int $blSupprime): self
    {
        $this->blSupprime = $blSupprime;

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

    public function getCdEtatSollicitation(): ?TrEtatSol
    {
        return $this->cdEtatSollicitation;
    }

    public function setCdEtatSollicitation(?TrEtatSol $cdEtatSollicitation): self
    {
        $this->cdEtatSollicitation = $cdEtatSollicitation;

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

    public function getIdPhaseRef(): ?TrPhase
    {
        return $this->idPhaseRef;
    }

    public function setIdPhaseRef(?TrPhase $idPhaseRef): self
    {
        $this->idPhaseRef = $idPhaseRef;

        return $this;
    }


}
