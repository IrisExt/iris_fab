<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgProjet
 *
 * @ORM\Table(name="tg_projet", uniqueConstraints={@ORM\UniqueConstraint(name="tg_projet_pk", columns={"id_projet"})}, indexes={@ORM\Index(name="eval_comite_fk", columns={"id_comite"}), @ORM\Index(name="a_pour_categorie_fk", columns={"id_cat_rd"}), @ORM\Index(name="utilise_l_instrument_fk", columns={"id_inst_fi"})})
 * @ORM\Entity
 */
class TgProjet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_projet_id_projet_seq", allocationSize=1, initialValue=1)
     */
    private $idProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_acro", type="string", length=40, nullable=true, options={"comment"="acronyme du projet"})
     *
     * @Assert\NotBlank(
     *     message="tgprojet.lb_acro.not_blank",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     *
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 40,
     *      minMessage = "tgprojet.lb_acro.min_length",
     *      maxMessage = "tgprojet.lb_acro.max_length",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     */
    private $lbAcro;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_titre_fr", type="string", length=50, nullable=true)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.lb_titre_fr.not_blank",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     *
     * )
     * @Assert\Length(
     *      min = 10,
     *      max = 50,
     *      minMessage = "tgprojet.lb_titre_fr.min_length",
     *      maxMessage = "tgprojet.lb_titre_fr.max_length",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     */
    private $lbTitreFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_titre_en", type="string", length=50, nullable=true, options={"comment"="titre du projet en anglais"})
     *
     * @Assert\Length(
     *      min = 10,
     *      max = 50,
     *      minMessage = "tgprojet.lb_titre_en.min_length",
     *      maxMessage = "tgprojet.lb_titre_en.max_length",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     */
    private $lbTitreEn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="no_duree", type="smallint", nullable=true, options={"comment"="durée du projet exprimé en mois"})
     *
     * @Assert\NotBlank(
     *     message="tgprojet.no_duree.not_blank",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     */
    private $noDuree;

    /**
     * @var int|null
     *
     * @ORM\Column(name="mnt_aide_prev", type="bigint", nullable=true, options={"comment"="Montant d'aide prévisionnel"})
     *
     * @Assert\NotBlank(
     *     message="tgprojet.mnt_aide_prev.not_blank",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     *
     */
    private $mntAidePrev;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_dem_label", type="boolean", nullable=true, options={"comment"="souhait du porteur de labelisation du projet"})
     */
    private $blDemLabel;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_dem_cofi", type="boolean", nullable=true)
     */
    private $blDemCofi;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_infra_recherche", type="boolean", nullable=true)
     */
    private $blInfraRecherche;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ord_phase", type="integer", nullable=true)
     */
    private $ordPhase;

    /**
     * @var \TrCatRd
     *
     * @ORM\ManyToOne(targetEntity="TrCatRd")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_rd", referencedColumnName="id_cat_rd")
     * })
     *
     * @Assert\NotBlank(
     *     message="tgprojet.cat_rd.not_blank",
     *     groups={}
     * )
     */
    private $idCatRd;

    /**
     * @var \TgComite
     *
     * @ORM\ManyToOne(targetEntity="TgComite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     *
     * @Assert\NotBlank(
     *     message="tgprojet.comite.not_blank",
     *     groups={"formulaire", "bloc_BlIdentProjType"}
     * )
     */
    private $idComite;

    /**
     * @var \TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    /**
     * @var \TrAgFi
     *
     * @ORM\ManyToOne(targetEntity="TrAgFi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_agence_fi", referencedColumnName="id_agence_fi")
     * })
     *
     * @Assert\NotBlank(
     *     message="tgprojet.id_agence_fi.not_blank",
     *     groups={"bloc_BlInstFiType_agence"}
     * )
     */
    private $idAgenceFi;

    /**
     * @var \TrInstFi
     *
     * @ORM\ManyToOne(targetEntity="TrInstFi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_inst_fi", referencedColumnName="id_inst_fi")
     * })
     *
     * @Assert\NotBlank(
     *     message="tgprojet.id_inst_fi.not_blank",
     *     groups={"formulaire", "bloc_BlInstFiType"}
     * )
     */
    private $idInfraFi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrPolComp", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_pol_proj",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_pole_comp", referencedColumnName="id_pole_comp")
     *   }
     * )
     *
     * @Assert\Collection(fields ={
     *     "id_pole_comp" = @Assert\NotBlank(
     *         message="tgprojet.id_pole_comp.not_blank",
     *         groups={"BlInfoComplType_pc"}
     *     )
     * },
     * allowMissingFields = false,
     * groups={"BlInfoComplType_pc"}
     * )
     */
    private $idPoleComp;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgNonSouhaite", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_proj_non_souhait",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_non_souhaite", referencedColumnName="id_non_souhaite")
     *   }
     * )
     */
    private $idNonSouhaite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgMotCleErc", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_mc_erc_proj",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_mc_erc", referencedColumnName="id_mc_erc")
     *   }
     * )
     *
     * @Assert\Collection(fields ={
     *     "id_mc_erc" = @Assert\NotBlank(
     *         message="tgprojet.mc_erc.not_blank",
     *         groups={"BlMotCleErcType_erc"}
     *     )
     * },
     *
     * allowMissingFields = false,
     * groups={"BlMotCleErcType_erc"}
     * )
     *
     */
    private $idMcErc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrCoFi", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_co_fi_proj",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_co_fi", referencedColumnName="id_co_fi")
     *   }
     * )
     *
     * @Assert\Collection(fields ={
     *     "id_co_fi" = @Assert\NotBlank(
     *         message="tgprojet.id_co_fi.not_blank",
     *         groups={"BlInfoComplType_co"}
     *     )
     * },
     * allowMissingFields = false,
     * groups={"BlInfoComplType_co"}
     * )
     */
    private $idCoFi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrInfRech", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_infra_proj",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_inf_rech", referencedColumnName="id_inf_rech")
     *   }
     * )
     *
     * @Assert\Collection(fields ={
     *     "id_inf_rech" = @Assert\NotBlank(
     *         message="tgprojet.id_inf_rech.not_blank",
     *         groups={"BlInfoComplType_ir"}
     *     )
     * },
     * allowMissingFields = false,
     * groups={"BlInfoComplType_ir"}
     * )
     */
    private $idInfRech;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgMcCes", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_proj_mc_ces",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_mc_ces", referencedColumnName="id_mc_ces")
     *   }
     * )
     *
     * @Assert\Collection(fields ={
     *     "id_mc_ces" = @Assert\NotBlank(
     *         message="tgprojet.mc_ces.not_blank",
     *         groups={"BlMotCleCesType_ces"}
     *     )
     * },
     * allowMissingFields = false,
     * groups={"BlMotCleCesType_ces"}
     * )
     */
    private $idMcCes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_doc_sc", type="string", nullable=true)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.lb_doc_sc.not_blank",
     *     groups={""}
     * )
     */
    private $lbPreproposition;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_annexe_preproposition", type="string", length=255, nullable=true, options={"comment" = "Libéllé annexe préproposition"})
     *
     * @Assert\NotBlank(
     *     message="tgprojet.annex_prep.not_blank",
     *     groups={""}
     * )
     */
    private $lbAnnexePreproposition;

    //    /**
    //     * @var bool|null
    //     *
    //     * @ORM\Column(name="coord_francais", type="boolean", nullable=true)
    //     */
    //    private $coordFr;
    //
    //    /**
    //     * @var bool|null
    //     *
    //     * @ORM\Column(name="coord_etranger", type="boolean", nullable=true)
    //     */
    //    private $coordEtr;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgFinanceur", inversedBy="idProjet")
     * @ORM\JoinTable(name="tl_financeur_projet",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_financeur", referencedColumnName="id_financeur")
     *   }
     * )
     * @Assert\NotBlank(
     *     message="tgprojet.id_agence_fi.not_blank",
     *     groups={"formulaire", "bloc_BlInstFiType"}
     * )
     */
    private $idFinanceur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgHabilitation", mappedBy="idProjet")
     */
    private $idHabilitation;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_depot_prec", type="boolean", nullable=true)
     */
    private $blDepotPrec;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_financement_prec", type="boolean", nullable=true)
     */
    private $blFinancementPrec;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_programme_invest", type="boolean", nullable=true)
     */
    private $blProgrammeInvest;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="porteur", referencedColumnName="id_personne")
     * })
     */
    private $porteur;

    /**
     * @var \TrStatutProjet
     *
     * @ORM\ManyToOne(targetEntity="TrStatutProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sts_projet", referencedColumnName="id_sts_projet")
     * })
     */
    private $idStsProjet;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rang", type="integer", nullable=true)
     */
    private $rang;

    /**
     * @var string|null
     *
     * @ORM\Column(name="typ_adm", type="string", length=3, nullable=true)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $typAdm;

    //    /**
    //     * @var \Doctrine\Common\Collections\Collection
    //     *
    //     * @ORM\ManyToMany(targetEntity="TrLangue", mappedBy="idProjet")
    //     */
    //    private $idLangue;

    /**
     * @var \TrLangue
     *
     * @ORM\ManyToOne(targetEntity="TrLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="TgResume", mappedBy="idProjet", cascade={"persist"})
     *
     *
     */
    private $tgResume;

    /**
     * @var int|null
     *
     * @ORM\Column(name="doc_sc_langue", type="integer", nullable=true)
     */
    private $docScLangue;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="TgDocument", mappedBy="idProjet", cascade={"persist"})
     *
     * @Assert\Valid
     * @Assert\NotBlank(
     *     message="tgprojet.doc.not_blank",
     *     groups={"formulaire", "bloc_BlDocumentType"}
     * )
     *
     */
    private $tgDocument;

    /**
     * @var TlAvisProjet
     * @ORM\OneToMany(targetEntity="TlAvisProjet", mappedBy="idProjet", cascade={"persist"})
     */
    private $avisProjet;

    /**
     * @var TlExpertProj
     * @ORM\OneToMany(targetEntity="TlExpertProj", mappedBy="idProjet", cascade={"persist"})
     */
    private $experProj;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TgAffectation", mappedBy="idProjet")
     */
    private $tgAffectations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idFinanceur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idHabilitation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idPoleComp = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idNonSouhaite = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idMcErc = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idCoFi = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idInfRech = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idMcCes = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->idLangue = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tgResume = new ArrayCollection();
        $this->tgDocument = new ArrayCollection();
        $this->avisProjet = new ArrayCollection();
        $this->experProj = new ArrayCollection();
        $this->tgAffectations = new ArrayCollection();
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function getLbAcro(): ?string
    {
        return $this->lbAcro;
    }

    public function setLbAcro(?string $lbAcro): self
    {
        $this->lbAcro = $lbAcro;

        return $this;
    }

    public function getLbTitreFr(): ?string
    {
        return $this->lbTitreFr;
    }

    public function setLbTitreFr(?string $lbTitreFr): self
    {
        $this->lbTitreFr = $lbTitreFr;

        return $this;
    }

    public function getLbTitreEn(): ?string
    {
        return $this->lbTitreEn;
    }

    public function setLbTitreEn(?string $lbTitreEn): self
    {
        $this->lbTitreEn = $lbTitreEn;

        return $this;
    }

    public function getNoDuree(): ?int
    {
        return $this->noDuree;
    }

    public function setNoDuree(?int $noDuree): self
    {
        $this->noDuree = $noDuree;

        return $this;
    }

    public function getMntAidePrev(): ?int
    {
        return $this->mntAidePrev;
    }

    public function setMntAidePrev(?int $mntAidePrev): self
    {
        $this->mntAidePrev = $mntAidePrev;

        return $this;
    }

    public function getBlDemLabel(): ?bool
    {
        return $this->blDemLabel;
    }

    public function setBlDemLabel(?bool $blDemLabel): self
    {
        $this->blDemLabel = $blDemLabel;

        return $this;
    }

    public function getBlDemCofi(): ?bool
    {
        return $this->blDemCofi;
    }

    public function setBlDemCofi(?bool $blDemCofi): self
    {
        $this->blDemCofi = $blDemCofi;

        return $this;
    }

    public function getBlDepotPrec(): ?bool
    {
        return $this->blDepotPrec;
    }

    public function setBlDepotPrec(?bool $blDepotPrec): self
    {
        $this->blDepotPrec = $blDepotPrec;

        return $this;
    }

    public function getBlFinancementPrec(): ?bool
    {
        return $this->blFinancementPrec;
    }

    public function setBlFinancementPrec(?bool $blFinancementPrec): self
    {
        $this->blFinancementPrec = $blFinancementPrec;

        return $this;
    }

    public function getBlProgrammeInvest(): ?bool
    {
        return $this->blProgrammeInvest;
    }

    public function setBlProgrammeInvest(?bool $blProgrammeInvest): self
    {
        $this->blProgrammeInvest = $blProgrammeInvest;

        return $this;
    }

    public function getOrdPhase(): ?int
    {
        return $this->ordPhase;
    }

    public function setOrdPhase(?int $ordPhase): self
    {
        $this->ordPhase = $ordPhase;

        return $this;
    }

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(?int $rang): self
    {
        $this->rang = $rang;

        return $this;
    }

    public function getTypAdm(): ?string
    {
        return $this->typAdm;
    }

    public function setTypAdm(?string $typAdm): self
    {
        $this->typAdm = $typAdm;

        return $this;
    }

    public function getIdCatRd(): ?TrCatRd
    {
        return $this->idCatRd;
    }

    public function setIdCatRd(?TrCatRd $idCatRd): self
    {
        $this->idCatRd = $idCatRd;

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

    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function getPorteur(): ?TgPersonne
    {
        return $this->porteur;
    }

    public function setPorteur(?TgPersonne $porteur): self
    {
        $this->porteur = $porteur;

        return $this;
    }

    public function getIdAgenceFi(): ?TrAgFi
    {
        return $this->idAgenceFi;
    }

    public function setIdAgenceFi(?TrAgFi $idAgenceFi): self
    {
        $this->idAgenceFi = $idAgenceFi;

        return $this;
    }

    public function getIdInfraFi(): ?TrInstFi
    {
        return $this->idInfraFi;
    }

    public function setIdInfraFi(?TrInstFi $idInfraFi): self
    {
        $this->idInfraFi = $idInfraFi;

        return $this;
    }

    /**
     * @return Collection|TrPolComp[]
     */
    public function getIdPoleComp(): Collection
    {
        return $this->idPoleComp;
    }

    public function addIdPoleComp(TrPolComp $idPoleComp): self
    {
        if (!$this->idPoleComp->contains($idPoleComp)) {
            $this->idPoleComp[] = $idPoleComp;
        }

        return $this;
    }

    public function removeIdPoleComp(TrPolComp $idPoleComp): self
    {
        if ($this->idPoleComp->contains($idPoleComp)) {
            $this->idPoleComp->removeElement($idPoleComp);
        }

        return $this;
    }

    public function getIdStsProjet(): ?TrStatutProjet
    {
        return $this->idStsProjet;
    }

    public function setIdStsProjet(?TrStatutProjet $idStsProjet): self
    {
        $this->idStsProjet = $idStsProjet;

        return $this;
    }

    /**
     * @return Collection|TgFinanceur[]
     */
    public function getIdFinanceur(): Collection
    {
        return $this->idFinanceur;
    }

    public function addIdFinanceur(TgFinanceur $idFinanceur): self
    {
        if (!$this->idFinanceur->contains($idFinanceur)) {
            $this->idFinanceur[] = $idFinanceur;
        }

        return $this;
    }

    public function removeIdFinanceur(TgFinanceur $idFinanceur): self
    {
        if ($this->idFinanceur->contains($idFinanceur)) {
            $this->idFinanceur->removeElement($idFinanceur);
        }

        return $this;
    }

    /**
     * @return Collection|TgHabilitation[]
     */
    public function getIdHabilitation(): Collection
    {
        return $this->idHabilitation;
    }

    public function addIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if (!$this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation[] = $idHabilitation;
            $idHabilitation->addIdProjet($this);
        }

        return $this;
    }

    public function removeIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if ($this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation->removeElement($idHabilitation);
            $idHabilitation->removeIdProjet($this);
        }

        return $this;
    }

    /**
     * @return Collection|TgNonSouhaite[]
     */
    public function getIdNonSouhaite(): Collection
    {
        return $this->idNonSouhaite;
    }

    public function addIdNonSouhaite(TgNonSouhaite $idNonSouhaite): self
    {
        if (!$this->idNonSouhaite->contains($idNonSouhaite)) {
            $this->idNonSouhaite[] = $idNonSouhaite;
        }

        return $this;
    }

    public function removeIdNonSouhaite(TgNonSouhaite $idNonSouhaite): self
    {
        if ($this->idNonSouhaite->contains($idNonSouhaite)) {
            $this->idNonSouhaite->removeElement($idNonSouhaite);
        }

        return $this;
    }

    /**
     * @return Collection|TgMcErc[]
     */
    public function getIdMcErc(): Collection
    {
        return $this->idMcErc;
    }

    public function addIdMcErc(TgMcErc $idMcErc): self
    {
        if (!$this->idMcErc->contains($idMcErc)) {
            $this->idMcErc[] = $idMcErc;
        }

        return $this;
    }

    public function removeIdMcErc(TgMcErc $idMcErc): self
    {
        if ($this->idMcErc->contains($idMcErc)) {
            $this->idMcErc->removeElement($idMcErc);
        }

        return $this;
    }

    /**
     * @return Collection|TrCoFi[]
     */
    public function getIdCoFi(): Collection
    {
        return $this->idCoFi;
    }

    public function addIdCoFi(TrCoFi $idCoFi): self
    {
        if (!$this->idCoFi->contains($idCoFi)) {
            $this->idCoFi[] = $idCoFi;
        }

        return $this;
    }

    public function removeIdCoFi(TrCoFi $idCoFi): self
    {
        if ($this->idCoFi->contains($idCoFi)) {
            $this->idCoFi->removeElement($idCoFi);
        }

        return $this;
    }

    /**
     * @return Collection|TrInfRech[]
     */
    public function getIdInfRech(): Collection
    {
        return $this->idInfRech;
    }

    public function addIdInfRech(TrInfRech $idInfRech): self
    {
        if (!$this->idInfRech->contains($idInfRech)) {
            $this->idInfRech[] = $idInfRech;
        }

        return $this;
    }

    public function removeIdInfRech(TrInfRech $idInfRech): self
    {
        if ($this->idInfRech->contains($idInfRech)) {
            $this->idInfRech->removeElement($idInfRech);
        }

        return $this;
    }

    /**
     * @return Collection|TgMcCes[]
     */
    public function getIdMcCes(): Collection
    {
        return $this->idMcCes;
    }

    public function addIdMcCe(TgMcCes $idMcCes): self
    {
        if (!$this->idMcCes->contains($idMcCes)) {
            $this->idMcCes[] = $idMcCes;
        }

        return $this;
    }

    public function removeIdMcCe(TgMcCes $idMcCes): self
    {
        if ($this->idMcCes->contains($idMcCes)) {
            $this->idMcCes->removeElement($idMcCes);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBlInfraRecherche(): ?bool
    {
        return $this->blInfraRecherche;
    }

    /**
     * @param bool|null $blInfraRecherche
     */
    public function setBlInfraRecherche(?bool $blInfraRecherche): void
    {
        $this->blInfraRecherche = $blInfraRecherche;
    }

    /**
     * @return string|null
     */
    public function getLbPreproposition(): ?string
    {
        return $this->lbPreproposition;
    }

    /**
     * @param string|null $lbPreproposition
     */
    public function setLbPreproposition(?string $lbPreproposition): void
    {
        $this->lbPreproposition = $lbPreproposition;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return TgProjet
     */
    public function setFile(?UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbAnnexePreproposition(): ?string
    {
        return $this->lbAnnexePreproposition;
    }

    /**
     * @param string|null $lbAnnexePreproposition
     */
    public function setLbAnnexePreproposition(?string $lbAnnexePreproposition): void
    {
        $this->lbAnnexePreproposition = $lbAnnexePreproposition;
    }

    //    /**
    //     * @return bool|null
    //     */
    //    public function getCoordFr(): ?bool
    //    {
    //        return $this->coordFr;
    //    }
    //
    //    /**
    //     * @param bool|null $coordFr
    //     */
    //    public function setCoordFr(?bool $coordFr): void
    //    {
    //        $this->coordFr = $coordFr;
    //    }
    //
    //    /**
    //     * @return bool|null
    //     */
    //    public function getCoordEtr(): ?bool
    //    {
    //        return $this->coordEtr;
    //    }
    //
    //    /**
    //     * @param bool|null $coordEtr
    //     */
    //    public function setCoordEtr(?bool $coordEtr): void
    //    {
    //        $this->coordEtr = $coordEtr;
    //    }

    //    /**
    //     * @return Collection|TrLangue[]
    //     */
    //    public function getIdLangue(): Collection
    //    {
    //        return $this->idLangue;
    //    }
    //
    //    public function addIdLangue(TrLangue $idLangue): self
    //    {
    //        if (!$this->idLangue->contains($idLangue)) {
    //            $this->idLangue[] = $idLangue;
    //            $idLangue->addIdProjet($this);
    //        }
    //
    //        return $this;
    //    }
    //
    //    public function removeIdLangue(TrLangue $idLangue): self
    //    {
    //        if ($this->idLangue->contains($idLangue)) {
    //            $this->idLangue->removeElement($idLangue);
    //            $idLangue->removeIdProjet($this);
    //        }
    //
    //        return $this;
    //    }

    public function __toString()
    {
        return $this->getIdProjet() . $this->getLbAcro() . $this->getLbTitreFr();
    }

    public function serialize()
    {
        return serialize(array(
            $this->lbPreproposition,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->lbPreproposition,
        ) = unserialize($serialized);
    }

    /**
     * @return Collection|TgResume[]
     */
    public function getTgResume(): Collection
    {
        return $this->tgResume;
    }

    public function addTgResume(TgResume $tgResume): self
    {
        if (!$this->tgResume->contains($tgResume)) {
            $this->tgResume[] = $tgResume;
            $tgResume->setIdProjet($this);
        }

        return $this;
    }

    public function removeTgResume(TgResume $tgResume): self
    {
        if ($this->tgResume->contains($tgResume)) {
            $this->tgResume->removeElement($tgResume);
            // set the owning side to null (unless already changed)
            if ($tgResume->getIdProjet() === $this) {
                $tgResume->setIdProjet(null);
            }
        }

        return $this;
    }

    public function getDocScLangue(): ?int
    {
        return $this->docScLangue;
    }

    public function setDocScLangue(?int $docScLangue): self
    {
        $this->docScLangue = $docScLangue;

        return $this;
    }

    /**
     * @return Collection|TgDocument[]
     */
    public function getTgDocument(): Collection
    {
        return $this->tgDocument;
    }

    public function addTgDocument(TgDocument $tgDocument): self
    {
        if (!$this->tgDocument->contains($tgDocument)) {
            $this->tgDocument[] = $tgDocument;
            $tgDocument->setIdProjet($this);
        }

        return $this;
    }

    public function removeTgDocument(TgDocument $tgDocument): self
    {
        if ($this->tgDocument->contains($tgDocument)) {
            $this->tgDocument->removeElement($tgDocument);
            // set the owning side to null (unless already changed)
            if ($tgDocument->getIdProjet() === $this) {
                $tgDocument->setIdProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrAvisProjet[]
     */
    public function getAvisProjet(): Collection
    {
        return $this->avisProjet;
    }

    public function addAvisProjet(TrAvisProjet $avisProjet): self
    {
        if (!$this->avisProjet->contains($avisProjet)) {
            $this->avisProjet[] = $avisProjet;
            $avisProjet->setIdProjet($this);
        }

        return $this;
    }

    public function removeAvisProjet(TrAvisProjet $avisProjet): self
    {
        if ($this->avisProjet->contains($avisProjet)) {
            $this->avisProjet->removeElement($avisProjet);
            // set the owning side to null (unless already changed)
            if ($avisProjet->getIdProjet() === $this) {
                $avisProjet->setIdProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlExpertProj[]
     */
    public function getExperProj(): Collection
    {
        return $this->experProj;
    }

    public function addExperProj(TlExpertProj $experProj): self
    {
        if (!$this->experProj->contains($experProj)) {
            $this->experProj[] = $experProj;
            $experProj->setIdProjet($this);
        }

        return $this;
    }

    public function removeExperProj(TlExpertProj $experProj): self
    {
        if ($this->experProj->contains($experProj)) {
            $this->experProj->removeElement($experProj);
            // set the owning side to null (unless already changed)
            if ($experProj->getIdProjet() === $this) {
                $experProj->setIdProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgAffectation[]
     */
    public function getTgAffectations(): Collection
    {
        return $this->tgAffectations;
    }

    public function addTgAffectation(TgAffectation $tgAffectation): self
    {
        if (!$this->tgAffectations->contains($tgAffectation)) {
            $this->tgAffectations[] = $tgAffectation;
            $tgAffectation->setTgProjet($this);
        }

        return $this;
    }

    public function removeTgAffectation(TgAffectation $tgAffectation): self
    {
        if ($this->tgAffectations->contains($tgAffectation)) {
            $this->tgAffectations->removeElement($tgAffectation);
            // set the owning side to null (unless already changed)
            if ($tgAffectation->getTgProjet() === $this) {
                $tgAffectation->setTgProjet(null);
            }
        }

        return $this;
    }

    public function getIdLangue(): ?TrLangue
    {
        return $this->idLangue;
    }

    public function setIdLangue(?TrLangue $idLangue): self
    {
        $this->idLangue = $idLangue;

        return $this;
    }
}
