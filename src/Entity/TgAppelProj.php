<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgAppelProj
 *
 * @ORM\Table(name="tg_appel_proj", uniqueConstraints={@ORM\UniqueConstraint(name="tg_appel_proj_g_pk", columns={"id_appel"})}, indexes={@ORM\Index(name="est_pilote_fk", columns={"pilote"})})
 * @ORM\Entity(repositoryClass="App\Repository\AppelProjetRepository")
 */
class TgAppelProj
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_appel", type="bigint", nullable=false, options={"comment"="identifiant de l'appel à projet"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_appel_proj_id_appel_seq", allocationSize=1, initialValue=1)
     */
    private $idAppel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_millesime", type="integer", nullable=false, options={"comment"="millesime de l'appel à projet; communement aussi appelé edition  par le metier"})
     * @Assert\Range(
     *      min = 2005,
     *      max = 2030,
     *      minMessage = "L'édition doit être supérieure ou égale à {{ limit }}",
     *      maxMessage = "L'édition doit être inférieur ou égale à {{ limit }}",

     * )
     */
    private $dtMillesime;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_appel", type="string", length=255, nullable=false, options={"comment"="Désignation de l'appel a projet"})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbAppel;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_acronyme", type="string", length=255, nullable=false, options={"comment"="Désignation abrégée du projet "})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbAcronyme;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_page_max_ds", type="integer", nullable=true)
     */
    private $nbPageMaxDs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_phase", type="integer", nullable=true)
     */
    private $nbPhase;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_annexe_autorise", type="smallint", nullable=true)
     */
    private $blAnnexeAutorise;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_demande_depot_prec", type="smallint", nullable=true)
     */
    private $blDemandeDepotPrec;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_demande_fi_prec", type="smallint", nullable=true)
     */
    private $blDemandeFiPrec;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_naiss_max_rs", type="date", nullable=true)
     */
    private $dtNaissMaxRs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_taille_max_ann_ds", type="integer", nullable=true)
     */
    private $nbTailleMaxAnnDs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fmt_autorise_annexe_ds", type="string", length=200, nullable=true)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $fmtAutoriseAnnexeDs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_min_mc_libre", type="integer", nullable=true)
     */
    private $nbMinMcLibre;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_max_mc_libre", type="integer", nullable=true)
     */
    private $nbMaxMcLibre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seuil_min_aide", type="decimal", precision=15, scale=0, nullable=true)
     */
    private $seuilMinAide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seuil_max_aide", type="decimal", precision=20, scale=0, nullable=true)
     */
    private $seuilMaxAide;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_sauve", type="date", nullable=true)
     */
    private $dhSauve;

    /**
     * @var TgNiveauPhase
     *
     * @ORM\ManyToOne(targetEntity="TgNiveauPhase" ,cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="niveau_en_cours", referencedColumnName="id_niveau_phase")
     * })
     */
    private $niveauEnCours;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_clo_fin", type="date", nullable=true, options={"comment"="Date de cloture de l'appel à projet"})
     *
     */
    private $dtCloFin;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pilote", referencedColumnName="id_personne")
     * })
     */
    private $pilote;

    /**
     * @var \TrCdConvention
     *
     * @ORM\ManyToOne(targetEntity="TrCdConvention")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cd_convention", referencedColumnName="id_cd_convention")
     * })
     */
    private $idCdConvention;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrClasseFormulaire", inversedBy="idAppel")
     * @ORM\JoinTable(name="tl_appel_classe_formulaire",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_classe_formulaire", referencedColumnName="id_classe_formulaire")
     *   }
     * )
     */
    private $idClasseFormulaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrCatRd", mappedBy="idAppel")
     */
    private $idCatRd;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrInstFi", inversedBy="idAppel")
     * @ORM\JoinTable(name="tl_inst_fi_appel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_inst_fi", referencedColumnName="id_inst_fi")
     *   }
     * )
     */
    private $idInstFi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrLangue", inversedBy="idAppel")
     * @ORM\JoinTable(name="tl_langue_proposee_ds",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     *   }
     * )
     */
    private $idLangue;

//    /**
//     * @var \Doctrine\Common\Collections\Collection
//     *
//     * @ORM\ManyToMany(targetEntity="TgMotCleErc", mappedBy="idAppel")
//     */
//    private $idMcErc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgHabilitation", inversedBy="idAppel")
     * @ORM\JoinTable(name="tl_hab_appel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_habilitation", referencedColumnName="id_habilitation")
     *   }
     * )
     */
    private $idHabilitation;

    /**
     * @var \TlFormulaireAppel
     * @ORM\OneToMany(targetEntity="App\Entity\TlFormulaireAppel", mappedBy="idAppel", cascade={"persist"})
     */
    private $tlFormulaireAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgReunion", mappedBy="idAppel", cascade={"persist"})
     */
    private $idReunion;

    /**
     * @var TlMcErcAppel
     * @ORM\OneToMany(targetEntity="TlMcErcAppel", mappedBy="idAppel", cascade={"persist"})
     */
    private $tlMcErcAppel;

    public function __construct()
    {
        $this->idHabilitation = new ArrayCollection();
        $this->idFormulaire = new ArrayCollection();
        $this->idClasseFormulaire = new ArrayCollection();
        $this->idCatRd = new ArrayCollection();
        $this->idInstFi = new ArrayCollection();
        $this->idLangue = new ArrayCollection();
        $this->idMcErc = new ArrayCollection();
        $this->idReunion = new ArrayCollection();
        $this->tlFormulaireAppel = new ArrayCollection();
        $this->tlMcErcAppel = new ArrayCollection();
    }


    public function __toString():string
    {
        return $this->getLbAcronyme();
    }

    public function getIdAppel(): ?int
    {
        return $this->idAppel;
    }

    public function getDtMillesime(): ?int
    {
        return $this->dtMillesime;
    }

    public function setDtMillesime(int $dtMillesime): self
    {
        $this->dtMillesime = $dtMillesime;

        return $this;
    }

    public function getLbAppel(): ?string
    {
        return $this->lbAppel;
    }

    public function setLbAppel(string $lbAppel): self
    {
        $this->lbAppel = $lbAppel;

        return $this;
    }

    public function getLbAcronyme(): ?string
    {
        return $this->lbAcronyme;
    }

    public function setLbAcronyme(string $lbAcronyme): self
    {
        $this->lbAcronyme = $lbAcronyme;

        return $this;
    }

    public function getDtCloFin(): ?\DateTimeInterface
    {
        return $this->dtCloFin;
    }

    public function setDtCloFin(?\DateTimeInterface $dtCloFin): self
    {
        $this->dtCloFin = $dtCloFin;

        return $this;
    }

    public function getNiveauEnCours(): ?TgNiveauPhase
    {
        return $this->niveauEnCours;
    }

    public function setNiveauEnCours(?TgNiveauPhase $niveauEnCours): self
    {
        $this->niveauEnCours = $niveauEnCours;

        return $this;
    }

    public function getPilote(): ?TgPersonne
    {
        return $this->pilote;
    }

    public function setPilote(?TgPersonne $pilote): self
    {
        $this->pilote = $pilote;

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
        }

        return $this;
    }

    public function removeIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if ($this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation->removeElement($idHabilitation);
        }

        return $this;
    }

    /**
     * @return Collection|TgFormulaire[]
     */
    public function getIdFormulaire(): Collection
    {
        return $this->idFormulaire;
    }

    public function addIdFormulaire(TgFormulaire $idFormulaire): self
    {
        if (!$this->idFormulaire->contains($idFormulaire)) {
            $this->idFormulaire[] = $idFormulaire;
        }

        return $this;
    }

    public function removeIdFormulaire(TgFormulaire $idFormulaire): self
    {
        if ($this->idFormulaire->contains($idFormulaire)) {
            $this->idFormulaire->removeElement($idFormulaire);
        }

        return $this;
    }

    public function getNbPageMaxDs(): ?int
    {
        return $this->nbPageMaxDs;
    }

    public function setNbPageMaxDs(?int $nbPageMaxDs): self
    {
        $this->nbPageMaxDs = $nbPageMaxDs;

        return $this;
    }

    public function getBlAnnexeAutorise(): ?int
    {
        return $this->blAnnexeAutorise;
    }

    public function setBlAnnexeAutorise(?int $blAnnexeAutorise): self
    {
        $this->blAnnexeAutorise = $blAnnexeAutorise;

        return $this;
    }

    public function getBlDemandeDepotPrec(): ?int
    {
        return $this->blDemandeDepotPrec;
    }

    public function setBlDemandeDepotPrec(?int $blDemandeDepotPrec): self
    {
        $this->blDemandeDepotPrec = $blDemandeDepotPrec;

        return $this;
    }

    public function getBlDemandeFiPrec(): ?int
    {
        return $this->blDemandeFiPrec;
    }

    public function setBlDemandeFiPrec(?int $blDemandeFiPrec): self
    {
        $this->blDemandeFiPrec = $blDemandeFiPrec;

        return $this;
    }

    public function getDtNaissMaxRs(): ?\DateTimeInterface
    {
        return $this->dtNaissMaxRs;
    }

    public function setDtNaissMaxRs(?\DateTimeInterface $dtNaissMaxRs): self
    {
        $this->dtNaissMaxRs = $dtNaissMaxRs;

        return $this;
    }

    public function getNbTailleMaxAnnDs(): ?int
    {
        return $this->nbTailleMaxAnnDs;
    }

    public function setNbTailleMaxAnnDs(?int $nbTailleMaxAnnDs): self
    {
        $this->nbTailleMaxAnnDs = $nbTailleMaxAnnDs;

        return $this;
    }

    public function getFmtAutoriseAnnexeDs(): ?string
    {
        return $this->fmtAutoriseAnnexeDs;
    }

    public function setFmtAutoriseAnnexeDs(?string $fmtAutoriseAnnexeDs): self
    {
        $this->fmtAutoriseAnnexeDs = $fmtAutoriseAnnexeDs;

        return $this;
    }

    public function getNbMinMcLibre(): ?int
    {
        return $this->nbMinMcLibre;
    }

    public function setNbMinMcLibre(?int $nbMinMcLibre): self
    {
        $this->nbMinMcLibre = $nbMinMcLibre;

        return $this;
    }

    public function getNbMaxMcLibre(): ?int
    {
        return $this->nbMaxMcLibre;
    }

    public function setNbMaxMcLibre(?int $nbMaxMcLibre): self
    {
        $this->nbMaxMcLibre = $nbMaxMcLibre;

        return $this;
    }

    public function getSeuilMinAide()
    {
        return $this->seuilMinAide;
    }

    public function setSeuilMinAide($seuilMinAide): self
    {
        $this->seuilMinAide = $seuilMinAide;

        return $this;
    }

    public function getSeuilMaxAide()
    {
        return $this->seuilMaxAide;
    }

    public function setSeuilMaxAide($seuilMaxAide): self
    {
        $this->seuilMaxAide = $seuilMaxAide;

        return $this;
    }

    public function getDhSauve(): ?\DateTimeInterface
    {
        return $this->dhSauve;
    }

    public function setDhSauve(?\DateTimeInterface $dhSauve): self
    {
        $this->dhSauve = $dhSauve;

        return $this;
    }

    public function getIdCdConvention(): ?TrCdConvention
    {
        return $this->idCdConvention;
    }

    public function setIdCdConvention(?TrCdConvention $idCdConvention): self
    {
        $this->idCdConvention = $idCdConvention;

        return $this;
    }

    /**
     * @return Collection|TrClasseFormulaire[]
     */
    public function getIdClasseFormulaire(): Collection
    {
        return $this->idClasseFormulaire;
    }

    public function addIdClasseFormulaire(TrClasseFormulaire $idClasseFormulaire): self
    {
        if (!$this->idClasseFormulaire->contains($idClasseFormulaire)) {
            $this->idClasseFormulaire[] = $idClasseFormulaire;
        }

        return $this;
    }

    public function removeIdClasseFormulaire(TrClasseFormulaire $idClasseFormulaire): self
    {
        if ($this->idClasseFormulaire->contains($idClasseFormulaire)) {
            $this->idClasseFormulaire->removeElement($idClasseFormulaire);
        }

        return $this;
    }

    /**
     * @return Collection|TrCatRd[]
     */
    public function getIdCatRd(): Collection
    {
        return $this->idCatRd;
    }

    public function addIdCatRd(TrCatRd $idCatRd): self
    {
        if (!$this->idCatRd->contains($idCatRd)) {
            $this->idCatRd[] = $idCatRd;
            $idCatRd->addIdAppel($this);
        }

        return $this;
    }

    public function removeIdCatRd(TrCatRd $idCatRd): self
    {
        if ($this->idCatRd->contains($idCatRd)) {
            $this->idCatRd->removeElement($idCatRd);
            $idCatRd->removeIdAppel($this);
        }

        return $this;
    }

    /**
     * @return Collection|TrInstFi[]
     */
    public function getIdInstFi(): Collection
    {
        return $this->idInstFi;
    }

    public function addIdInstFi(TrInstFi $idInstFi): self
    {
        if (!$this->idInstFi->contains($idInstFi)) {
            $this->idInstFi[] = $idInstFi;
        }

        return $this;
    }

    public function removeIdInstFi(TrInstFi $idInstFi): self
    {
        if ($this->idInstFi->contains($idInstFi)) {
            $this->idInstFi->removeElement($idInstFi);
        }

        return $this;
    }

    /**
     * @return Collection|TrLangue[]
     */
    public function getIdLangue(): Collection
    {
        return $this->idLangue;
    }

    public function addIdLangue(TrLangue $idLangue): self
    {
        if (!$this->idLangue->contains($idLangue)) {
            $this->idLangue[] = $idLangue;
        }

        return $this;
    }

    public function removeIdLangue(TrLangue $idLangue): self
    {
        if ($this->idLangue->contains($idLangue)) {
            $this->idLangue->removeElement($idLangue);
        }

        return $this;
    }

    /**
     * @return Collection|TgMotCleErc[]
     */
    public function getIdMcErc(): Collection
    {
        return $this->idMcErc;
    }

    public function addIdMcErc(TgMotCleErc $idMcErc): self
    {
        if (!$this->idMcErc->contains($idMcErc)) {
            $this->idMcErc[] = $idMcErc;
            $idMcErc->addIdAppel($this);
        }

        return $this;
    }

    public function removeIdMcErc(TgMotCleErc $idMcErc): self
    {
        if ($this->idMcErc->contains($idMcErc)) {
            $this->idMcErc->removeElement($idMcErc);
            $idMcErc->removeIdAppel($this);
        }

        return $this;
    }

    public function getNbPhase(): ?int
    {
        return $this->nbPhase;
    }

    public function setNbPhase(?int $nbPhase): self
    {
        $this->nbPhase = $nbPhase;

        return $this;
    }

    /**
     * @return Collection|TgReunion[]
     */
    public function getIdReunion(): Collection
    {
        return $this->idReunion;
    }

    public function addIdReunion(TgReunion $idReunion): self
    {
        if (!$this->idReunion->contains($idReunion)) {
            $this->idReunion[] = $idReunion;
            $idReunion->setIdAppel($this);
        }

        return $this;
    }

    public function removeIdReunion(TgReunion $idReunion): self
    {
        if ($this->idReunion->contains($idReunion)) {
            $this->idReunion->removeElement($idReunion);
            // set the owning side to null (unless already changed)
            if ($idReunion->getIdAppel() === $this) {
                $idReunion->setIdAppel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlFormulaireAppel[]
     */
    public function getTlFormulaireAppel(): Collection
    {
        return $this->tlFormulaireAppel;
    }

    public function addTlFormulaireAppel(TlFormulaireAppel $tlFormulaireAppel): self
    {
        if (!$this->tlFormulaireAppel->contains($tlFormulaireAppel)) {
            $this->tlFormulaireAppel[] = $tlFormulaireAppel;
            $tlFormulaireAppel->setIdAppel($this);
        }

        return $this;
    }

    public function removeTlFormulaireAppel(TlFormulaireAppel $tlFormulaireAppel): self
    {
        if ($this->tlFormulaireAppel->contains($tlFormulaireAppel)) {
            $this->tlFormulaireAppel->removeElement($tlFormulaireAppel);
            // set the owning side to null (unless already changed)
            if ($tlFormulaireAppel->getIdAppel() === $this) {
                $tlFormulaireAppel->setIdAppel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlMcErcAppel[]
     */
    public function getTlMcErcAppel(): Collection
    {
        return $this->tlMcErcAppel;
    }

    public function addTlMcErcAppel(TlMcErcAppel $tlMcErcAppel): self
    {
        if (!$this->tlMcErcAppel->contains($tlMcErcAppel)) {
            $this->tlMcErcAppel[] = $tlMcErcAppel;
            $tlMcErcAppel->setIdAppel($this);
        }

        return $this;
    }

    public function removeTlMcErcAppel(TlMcErcAppel $tlMcErcAppel): self
    {
        if ($this->tlMcErcAppel->contains($tlMcErcAppel)) {
            $this->tlMcErcAppel->removeElement($tlMcErcAppel);
            // set the owning side to null (unless already changed)
            if ($tlMcErcAppel->getIdAppel() === $this) {
                $tlMcErcAppel->setIdAppel(null);
            }
        }

        return $this;
    }


}
