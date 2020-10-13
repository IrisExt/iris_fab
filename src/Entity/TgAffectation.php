<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgAffectation
 *
 * @ORM\Table(name="tg_affectation", uniqueConstraints={@ORM\UniqueConstraint(name="tg_evaluation_pk", columns={"id_projet", "id_personne"})}, indexes={@ORM\Index(name="est_pour_fk", columns={"id_projet"}), @ORM\Index(name="a_pour_role_fk", columns={"id_profil"}), @ORM\Index(name="est_pour_personne_fk", columns={"id_personne"}), @ORM\Index(name="est_sollicite_fk", columns={"cd_sollicitation"}), @ORM\Index(name="a_pour_statut_avancement_fk", columns={"cd_sts_evaluation"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgAffectationRepository")
 */
class TgAffectation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_affectation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_affectation_id_affectation_seq", allocationSize=1, initialValue=1)
     */
    private $idAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_cause", type="text", nullable=true)
     */
    private $lbCause;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_new_modif", type="boolean", nullable=true, options={"default":false},options={"comment"="Indique si l'�valuation a �t� consult�e depuis la derniere modification de l'�tat de l'�valuation"})
     */
    private $blNewModif = false;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_doc_telecharge", type="boolean", nullable=true, options={"default":false})
     */
    private $blDocTelecharge = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_echeance", type="date", nullable=true, options={"comment"="date et heure pr�vue de rendu de l'�valuation (au niveau de l'�valuateur)"})
     */
    private $dhEcheance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="txt_commentaire", type="text", nullable=true)
     */
    private $txtCommentaire;

    /**
     * @var \TrProfil
     *
     * @ORM\ManyToOne(targetEntity="TrProfil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil")
     * })
     */
    private $idProfil;

    /**
     * @var TrStAffect
     *
     * @ORM\ManyToOne(targetEntity="TrStAffect")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_st_affect", referencedColumnName="id_st_affect")
     * })
     */
    private $idStAffect;

    /**
     * @var \TrStsEvaluation
     *
     * @ORM\ManyToOne(targetEntity="TrStsEvaluation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_sts_evaluation", referencedColumnName="cd_sts_evaluation")
     * })
     */
    private $cdStsEvaluation;

    /**
     * @var \TgProjet
     *
     * @ORM\ManyToOne(targetEntity="TgProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

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
     * @var TrStsSollicitation
     *
     * @ORM\ManyToOne(targetEntity="TrStsSollicitation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_sollicitation", referencedColumnName="cd_sollicitation")
     * })
     */
    private $cdSollicitation;

    /**
     * @var \TrTypeEval
     *
     * @ORM\ManyToOne(targetEntity="TrTypeEval")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type", referencedColumnName="id_type")
     * })
     */
    private $idType;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_propose", referencedColumnName="id_personne")
     * })
     */
    private $idPropose;

    /**
     * @return int
     */
    public function getIdAffectation(): int
    {
        return $this->idAffectation;
    }

    public function getLbCause(): ?string
    {
        return $this->lbCause;
    }

    public function setLbCause(?string $lbCause): self
    {
        $this->lbCause = $lbCause;

        return $this;
    }

    public function getBlNewModif(): ?bool
    {
        return $this->blNewModif;
    }

    public function setBlNewModif(?bool $blNewModif): self
    {
        $this->blNewModif = $blNewModif;

        return $this;
    }

    public function getBlDocTelecharge(): ?bool
    {
        return $this->blDocTelecharge;
    }

    public function setBlDocTelecharge(?bool $blDocTelecharge): self
    {
        $this->blDocTelecharge = $blDocTelecharge;

        return $this;
    }

    public function getDhEcheance(): ?\DateTimeInterface
    {
        return $this->dhEcheance;
    }

    public function setDhEcheance(?\DateTimeInterface $dhEcheance): self
    {
        $this->dhEcheance = $dhEcheance;

        return $this;
    }

    public function getTxtCommentaire(): ?string
    {
        return $this->txtCommentaire;
    }

    public function setTxtCommentaire(?string $txtCommentaire): self
    {
        $this->txtCommentaire = $txtCommentaire;

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

    public function getIdStAffect(): ?TrStAffect
    {
        return $this->idStAffect;
    }

    public function setIdStAffect(?TrStAffect $idStAffect): self
    {
        $this->idStAffect = $idStAffect;

        return $this;
    }

    public function getCdStsEvaluation(): ?TrStsEvaluation
    {
        return $this->cdStsEvaluation;
    }

    public function setCdStsEvaluation(?TrStsEvaluation $cdStsEvaluation): self
    {
        $this->cdStsEvaluation = $cdStsEvaluation;

        return $this;
    }

    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

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

    public function getCdSollicitation(): ?TrStsSollicitation
    {
        return $this->cdSollicitation;
    }

    public function setCdSollicitation(?TrStsSollicitation $cdSollicitation): self
    {
        $this->cdSollicitation = $cdSollicitation;

        return $this;
    }

    public function getIdType(): ?TrTypeEval
    {
        return $this->idType;
    }

    public function setIdType(?TrTypeEval $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdPropose(): ?TgPersonne
    {
        return $this->idPropose;
    }

    public function setIdPropose(?TgPersonne $idPropose): self
    {
        $this->idPropose = $idPropose;

        return $this;
    }

    public function __toString(): string
    {
        return $this->idPersonne->getNomUsage();
    }


}
