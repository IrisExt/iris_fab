<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgPartenariat
 *
 * @ORM\Table(name="tg_partenariat", uniqueConstraints={@ORM\UniqueConstraint(name="tg_partenariat_pk", columns={"id_partenaire"})}, indexes={@ORM\Index(name="directeur_de_labo_fk", columns={"dir_labo"}), @ORM\Index(name="est_hebergeur_fk", columns={"hebergeur"}), @ORM\Index(name="gest_adm_fi_fk", columns={"gest_adm"}), @ORM\Index(name="tutelle_gestionnaire_fk", columns={"tut_gest"}), @ORM\Index(name="est_representant_juridique_fk", columns={"rep_juridique"}), @ORM\Index(name="a_pour_cout_fk", columns={"id_cout_prv"}), @ORM\Index(name="a_pour_laboratoire_fk", columns={"laboratoire"}), @ORM\Index(name="participe_au_projet_fk", columns={"id_projet"}), @ORM\Index(name="est_resp_scientifique_fk", columns={"resp_scient"}), @ORM\Index(name="a_pour_type_fk", columns={"typ_part"})})
 * @ORM\Entity
 */
class TgPartenariat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_partenaire", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_partenariat_id_partenaire_seq", allocationSize=1, initialValue=1)
     */
    private $idPartenaire;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bl_actif", type="bigint", nullable=true, options={"comment"="Précise si le comité est actif ou supprimé (valeur 0)"})
     */
    private $blActif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_deleguation", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbDeleguation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_aide", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntAide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tx_aide", type="decimal", precision=4, scale=0, nullable=true)
     */
    private $txAide;

    /**
     * @var \TgCoutPrev
     *
     * @ORM\ManyToOne(targetEntity="TgCoutPrev", cascade={"remove","persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cout_prv", referencedColumnName="id_cout_prv")
     * })
     */
    private $idCoutPrv;

    /**
     * @var \TgOrganisme
     *
     * @ORM\ManyToOne(targetEntity="TgOrganisme", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="laboratoire", referencedColumnName="id_organisme")
     * })
     */
    private $laboratoire;

    /**
     * @var \TrTypePart
     *
     * @ORM\ManyToOne(targetEntity="TrTypePart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="typ_part", referencedColumnName="typ_part")
     * })
     */
    private $typPart;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dir_labo", referencedColumnName="id_personne")
     * })
     */
    private $dirLabo;

    /**
     * @var \TgOrganisme
     *
     * @ORM\ManyToOne(targetEntity="TgOrganisme", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hebergeur", referencedColumnName="id_organisme")
     * })
     */
    private $hebergeur;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rep_juridique", referencedColumnName="id_personne")
     * })
     */
    private $repJuridique;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resp_scient", referencedColumnName="id_personne")
     * })
     */
    private $respScient;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gest_adm", referencedColumnName="id_personne")
     * })
     */
    private $gestAdm;

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
     * @var \TgOrganisme
     *
     * @ORM\ManyToOne(targetEntity="TgOrganisme", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tut_gest", referencedColumnName="id_organisme")
     * })
     */
    private $tutGest;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgPersonne", inversedBy="idPartenaire")
     * @ORM\JoinTable(name="tl_pers_part",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_partenaire", referencedColumnName="id_partenaire")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     *   }
     * )
     */
    private $idPersonne;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tx_frais_env", type="decimal", precision=1, scale=0, nullable=true)
     */
    private $txFraisEnv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tx_frais_pers", type="decimal", precision=2, scale=0, nullable=true)
     */
    private $txFraisPers;

    /**
     * @var string|null
     *
     * @ORM\Column(name="autres_dep", type="decimal", precision=1, scale=0, nullable=true)
     */
    private $autresDep;

    /**
     * @var \TgCompteBancaire
     *
     * @ORM\ManyToOne(targetEntity="TgCompteBancaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_compte", referencedColumnName="id_compte")
     * })
     */
    private $idCompte;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idPersonne = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdPartenaire(): ?int
    {
        return $this->idPartenaire;
    }

    public function getBlActif(): ?int
    {
        return $this->blActif;
    }

    public function setBlActif(?int $blActif): self
    {
        $this->blActif = $blActif;

        return $this;
    }

    public function getLbDeleguation(): ?string
    {
        return $this->lbDeleguation;
    }

    public function setLbDeleguation(?string $lbDeleguation): self
    {
        $this->lbDeleguation = $lbDeleguation;

        return $this;
    }

    public function getMntAide()
    {
        return $this->mntAide;
    }

    public function setMntAide($mntAide): self
    {
        $this->mntAide = $mntAide;

        return $this;
    }

    public function getTxAide()
    {
        return $this->txAide;
    }

    public function setTxAide($txAide): self
    {
        $this->txAide = $txAide;

        return $this;
    }

    public function getIdCoutPrv(): ?TgCoutPrev
    {
        return $this->idCoutPrv;
    }

    public function setIdCoutPrv(?TgCoutPrev $idCoutPrv): self
    {
        $this->idCoutPrv = $idCoutPrv;

        return $this;
    }

    public function getLaboratoire(): ?TgOrganisme
    {
        return $this->laboratoire;
    }

    public function setLaboratoire(?TgOrganisme $laboratoire): self
    {
        $this->laboratoire = $laboratoire;

        return $this;
    }

    public function getTypPart(): ?TrTypePart
    {
        return $this->typPart;
    }

    public function setTypPart(?TrTypePart $typPart): self
    {
        $this->typPart = $typPart;

        return $this;
    }

    public function getDirLabo(): ?TgPersonne
    {
        return $this->dirLabo;
    }

    public function setDirLabo(?TgPersonne $dirLabo): self
    {
        $this->dirLabo = $dirLabo;

        return $this;
    }

    public function getHebergeur(): ?TgOrganisme
    {
        return $this->hebergeur;
    }

    public function setHebergeur(?TgOrganisme $hebergeur): self
    {
        $this->hebergeur = $hebergeur;

        return $this;
    }

    public function getRepJuridique(): ?TgPersonne
    {
        return $this->repJuridique;
    }

    public function setRepJuridique(?TgPersonne $repJuridique): self
    {
        $this->repJuridique = $repJuridique;

        return $this;
    }

    public function getRespScient(): ?TgPersonne
    {
        return $this->respScient;
    }

    public function setRespScient(?TgPersonne $respScient): self
    {
        $this->respScient = $respScient;

        return $this;
    }

    public function getGestAdm(): ?TgPersonne
    {
        return $this->gestAdm;
    }

    public function setGestAdm(?TgPersonne $gestAdm): self
    {
        $this->gestAdm = $gestAdm;

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

    public function getTutGest(): ?TgOrganisme
    {
        return $this->tutGest;
    }

    public function setTutGest(?TgOrganisme $tutGest): self
    {
        $this->tutGest = $tutGest;

        return $this;
    }

    /**
     * @return Collection|TgPersonne[]
     */
    public function getIdPersonne(): Collection
    {
        return $this->idPersonne;
    }

    public function addIdPersonne(TgPersonne $idPersonne): self
    {
        if (!$this->idPersonne->contains($idPersonne)) {
            $this->idPersonne[] = $idPersonne;
        }

        return $this;
    }

    public function removeIdPersonne(TgPersonne $idPersonne): self
    {
        if ($this->idPersonne->contains($idPersonne)) {
            $this->idPersonne->removeElement($idPersonne);
        }

        return $this;
    }

    public function getTxFraisEnv()
    {
        return $this->txFraisEnv;
    }

    public function setTxFraisEnv($txFraisEnv): self
    {
        $this->txFraisEnv = $txFraisEnv;

        return $this;
    }

    public function getTxFraisPers()
    {
        return $this->txFraisPers;
    }

    public function setTxFraisPers($txFraisPers): self
    {
        $this->txFraisPers = $txFraisPers;

        return $this;
    }

    public function getAutresDep()
    {
        return $this->autresDep;
    }

    public function setAutresDep($autresDep): self
    {
        $this->autresDep = $autresDep;

        return $this;
    }

    public function getIdCompte(): ?TgCompteBancaire
    {
        return $this->idCompte;
    }

    public function setIdCompte(?TgCompteBancaire $idCompte): self
    {
        $this->idCompte = $idCompte;

        return $this;
    }

}
