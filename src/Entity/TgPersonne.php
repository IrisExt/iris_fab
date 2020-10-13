<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgPersonne
 *
 * @ORM\Table(name="tg_personne", uniqueConstraints={@ORM\UniqueConstraint(name="tg_personne_pk", columns={"id_personne"})}, indexes={@ORM\Index(name="a_pour_civilite_fk", columns={"id_civilite"}), @ORM\Index(name="a_pour_genre_fk", columns={"id_genre"}), @ORM\Index(name="est_saisi_par_cps_fk", columns={"id_pers_cps"})})
 * @ORM\Entity(repositoryClass="App\Repository\PersonneRepository")
 */
class TgPersonne
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_personne", type="bigint", nullable=false, options={"comment"="Identifiant d' une personne en lien avec le référentiel personne (en cours de développement 14/05/2019)"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_personne_id_personne_seq", allocationSize=1, initialValue=1)
     */
    private $idPersonne;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_usage", type="string", length=50, nullable=false, options={"comment"="Nom d'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)"})
     *
     * @Assert\NotBlank(
     *     message="tgpersonne.lbnomusage.not_blank",
     *     groups={"ParticipantType"}
     *
     * )
     * @Assert\Length(
     *      max = 25,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *     groups={"ParticipantType"}
     * )
     */
    private $lbNomUsage;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_prenom", type="string", length=50, nullable=false, options={"comment"="Prénom d'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)"})
     *
     * @Assert\NotBlank(
     *     message="tgpersonne.lbprenom.not_blank",
     *     groups={"ParticipantType", "PersonneUpdateNameType"}
     *
     * )
     * @Assert\Length(
     *      max = 25,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *      groups={"ParticipantType"}
     * )
     */
    private $lbPrenom;

    /**
     * @var \TrGenre
     *
     * @ORM\ManyToOne(targetEntity="TrGenre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genre", referencedColumnName="id_genre")
     * })
     */
    private $idGenre;

    /**
     * @var string
     * @ORM\Column(name="cd_francophone", type="string", length=3, nullable=true)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $cdFrancophone;

    /**
     * @var string
     * @ORM\Column(name="lb_web_perso", type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbWebPerso;

    /**
     * @var string
     * @ORM\Column(name="fonction", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $fonction;

    /**
     * @var TrDispoComite
     *
     * @ORM\ManyToOne(targetEntity="TrDispoComite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dispo_comite", referencedColumnName="id_choix")
     * })
     */
    private $idDispoComite;

    /**
     * @var TgCv
     *
     * @ORM\OneToOne(targetEntity="TgCv", inversedBy="idPersonne", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cv", referencedColumnName="id_cv")
     * })
     */
    private $idCv;

//    /**
//     * @var string|null
//     *
//     * @ORM\Column(name="orcid", type="string", length=30, nullable=true)
//     *
//     * @Assert\Type(type="integer")
//     * @Assert\Length(
//     *      min = 16,
//     *      max = 16,
//     *      minMessage = "Le titire est limité à {{ limit }} caractères",
//     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
//     *      groups={"ParticipantType"}
//     * )
//     */
//    private $orcid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dt_soutenance_these", type="bigint", nullable=true)
     */
    private $dtSoutenanceThese;


    /**
     * @var \TrCivilite
     *
     * @ORM\ManyToOne(targetEntity="TrCivilite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_civilite", referencedColumnName="id_civilite")
     * })
     */
    private $idCivilite;

    /**
     * @var TgPersCps
     *
     * @ORM\OneToOne(targetEntity="TgPersCps", inversedBy="idPersonne", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(nullable=true, name="id_pers_cps", referencedColumnName="id_pers_cps")
     * })
     */
    private $idPersCps;


    /**
     * @var TlPersonneMclibre
     * @ORM\OneToMany(targetEntity="App\Entity\TlPersOrg", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $tlPersOrg;

    /**
     * @var \User

     * @ORM\OneToOne(targetEntity="User", mappedBy="idPersonne", cascade={"persist", "remove"})
     *
     */
    private $Users;

    /**
     * @ORM\OneToMany(targetEntity="TgAdresse", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $idAdresse;

    /**
     * @var TlReuPers
     * @ORM\OneToMany(targetEntity="TlReuPers", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $tlReuPers;


    /**
     * @var TgMotCleCps
     * @ORM\OneToMany(targetEntity="TgMotCleCps", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $idMcCps;

    /**
     * @var TgMotCleCv
     * @ORM\OneToMany(targetEntity="TgMotCleCv", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $idMcCv;

    /**
     * @var TlPersonneMcErc
     * @ORM\OneToMany(targetEntity="TlPersonneMcErc", mappedBy="idPersonne", cascade={"persist"})
     */
    private $tlPersonneMcErc;

    /**
     * @var TlPersonneMclibre
     * @ORM\OneToMany(targetEntity="TlPersonneMcLibre", mappedBy="idPersonne", cascade={"persist"})
     */
    private $tlPersonneMcLibre;

    /**
     * @var TgAdrMail
     * @ORM\OneToMany(targetEntity="TgAdrMail", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $idAdrMail;

//    /**
//     * @var TlLangPers
//     * @ORM\OneToMany(targetEntity="TlLangPers", mappedBy="idPersonne", cascade={"persist"})
//     */
//    private $idLangPers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgPartenariat", mappedBy="idPersonne")
     */
    private $idPartenaire;

    /**
     * @var TgHabilitation
     * @ORM\OneToMany(targetEntity="TgHabilitation", mappedBy="idPersonne", cascade={"persist", "remove"})
     */

    private $idHabilitation;

    /**
     * @var bool
     *
     * @ORM\Column(name="cv_renseigne", type="boolean", options={"default":"1"}))
     */
    private $cvRenseigne = true;

    /**
     * @var TgTelephone
     * @ORM\OneToMany(targetEntity="TgTelephone", mappedBy="idPersonne", cascade={"persist", "remove"})
     */
    private $noTelephone;

    /**
     * @var TgAdrMailNotification
     * @ORM\OneToMany(targetEntity="TgAdrMailNotification", mappedBy="idPersonne", cascade={"persist"})
     */
    private $adrMailNotif;

    /**
     * @var TgIdExternes
     * @ORM\OneToMany(targetEntity="App\Entity\TgIdExternes", mappedBy="idPersonne", cascade={"persist", "remove"})
     */

    private $idIdentExt;

    /**
     * @var TlAvisProjet
     * @ORM\OneToMany(targetEntity="TlAvisProjet", mappedBy="idPersonne", cascade={"persist"})
     */
    private $avisProjet;

    /**
     * @var TlExpertProj
     * @ORM\OneToMany(targetEntity="TlExpertProj", mappedBy="idPersonne", cascade={"persist"})
     */
    private $experProj;

    /**
     * Constructor
     */
    public function __construct()
    {


        $this->tlReuPers = new ArrayCollection();
        $this->idAdresse = new ArrayCollection();
        $this->idMcCps = new ArrayCollection();
        $this->idAdrMail = new ArrayCollection();
        $this->idLangPers = new ArrayCollection();
        $this->idHabilitation = new ArrayCollection();
        $this->idPartenaire = new ArrayCollection();
        $this->noTelephone = new ArrayCollection();
        $this->idMcErc = new ArrayCollection();
        $this->tlPersonneMcErc = new ArrayCollection();
        $this->tlPersonneMcLibre = new ArrayCollection();
        $this->tlPersOrg = new ArrayCollection();
        $this->idMcCv = new ArrayCollection();
        $this->tlPersAdrMailNotif = new ArrayCollection();
        $this->adrMailNotif = new ArrayCollection();
        $this->idIdentExt = new ArrayCollection();
        $this->avisProjet = new ArrayCollection();
        $this->experProj = new ArrayCollection();
    }

    public function setIdPersonne(int $idPersonne): self
    {
        $this->idPersonne = $idPersonne;
        return $this;
    }
    public function getIdPersonne(): ?int
    {
        return $this->idPersonne;
    }

    public function getLbNomUsage(): ?string
    {
        return $this->lbNomUsage;
    }

    public function setLbNomUsage(string $lbNomUsage): self
    {
        $this->lbNomUsage = $lbNomUsage;

        return $this;
    }

    public function getLbPrenom(): ?string
    {
        return $this->lbPrenom;
    }

    public function setLbPrenom(string $lbPrenom): self
    {
        $this->lbPrenom = $lbPrenom;

        return $this;
    }

    public function getBlFm(): ?string
    {
        return $this->blFm;
    }

    public function setBlFm(string $blFm): self
    {
        $this->blFm = $blFm;

        return $this;
    }

    public function getCdTypePers(): ?string
    {
        return $this->cdTypePers;
    }

    public function setCdTypePers(?string $cdTypePers): self
    {
        $this->cdTypePers = $cdTypePers;

        return $this;
    }

    public function getIdCivilite(): ?TrCivilite
    {
        return $this->idCivilite;
    }

    public function setIdCivilite(?TrCivilite $idCivilite): self
    {
        $this->idCivilite = $idCivilite;

        return $this;
    }




    /**
     * @return \User
     */
    public function getUsers(): ?User
    {
        return $this->Users;
    }

    /**
     * @param \User $Users
     */
    public function setUsers(User $Users): void
    {
        $this->Users = $Users;
    }


    /**
     * @return string
     */
    public function getCdFrancophone(): ? string
    {
        return $this->cdFrancophone;
    }


    /**
     * @param string $cdFrancophone
     */
    public function setCdFrancophone(string $cdFrancophone): self
    {
        $this->cdFrancophone = $cdFrancophone;

        return $this;
    }

    /**
     * @return string
     */
    public function getLbWebPerso(): ? string
    {
        return $this->lbWebPerso;
    }

    /**
     * @param string $lbWebPerso
     */
    public function setLbWebPerso(?string $lbWebPerso): self
    {
        $this->lbWebPerso = $lbWebPerso;

        return $this;
    }

    /**
     * @return string
     */
    public function getFonction(): ? string
    {
        return $this->fonction;
    }

    /**
     * @param string $fonction
     */
    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getIdGenre(): ?TrGenre
    {
        return $this->idGenre;
    }


    public function setIdGenre(?TrGenre $idGenre): self
    {
        $this->idGenre = $idGenre;

        return $this;
    }


//    /**
//     * @return string|null
//     */
//    public function getOrcid(): ?string
//    {
//        return $this->orcid;
//    }
//
//    /**
//     * @param string|null $orcid
//     */
//    public function setOrcid(?string $orcid): self
//    {
//        $this->orcid = $orcid;
//
//        return $this;
//    }

    /**
     * @return int|null
     */
    public function getDtSoutenanceThese(): ?int
    {
        return $this->dtSoutenanceThese;
    }

    /**
     * @param int|null $dtSoutenanceThese
     */
    public function setDtSoutenanceThese(?int $dtSoutenanceThese): self
    {
        $this->dtSoutenanceThese = $dtSoutenanceThese;

        return $this;
    }

    /**
     * @return TgPersCps|null
     */
    public function getIdPersCps(): ?TgPersCps
    {
        return $this->idPersCps;
    }

    /**
     * @param TgPersCps|null $idPersCps
     * @return TgPersonne
     */
    public function setIdPersCps(?TgPersCps $idPersCps): self
    {
        $this->idPersCps = $idPersCps;

        return $this;

    }

    /**
     * @return Collection|TgAdresse[]
     */
    public function getIdAdresse(): Collection
    {
        return $this->idAdresse;
    }

    public function addIdAdresse(TgAdresse $idAdresse): self
    {
        if (!$this->idAdresse->contains($idAdresse)) {
            $this->idAdresse[] = $idAdresse;
            $idAdresse->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdAdresse(TgAdresse $idAdresse): self
    {
        if ($this->idAdresse->contains($idAdresse)) {
            $this->idAdresse->removeElement($idAdresse);
            // set the owning side to null (unless already changed)
            if ($idAdresse->getIdPersonne() === $this) {
                $idAdresse->setIdPersonne(null);
            }
        }

        return $this;
    }




    public function __toString(): string
    {
        return $this->getLbPrenom(). ' ' . $this->getLbNomUsage();
    }



    /**
     * @return Collection|TlReuPers[]
     */
    public function getTlReuPers(): Collection
    {
        return $this->tlReuPers;
    }

    public function addTlReuPer(TlReuPers $tlReuPer): self
    {
        if (!$this->tlReuPers->contains($tlReuPer)) {
            $this->tlReuPers[] = $tlReuPer;
            $tlReuPer->setIdPersonne($this);
        }

        return $this;
    }

    public function removeTlReuPer(TlReuPers $tlReuPer): self
    {
        if ($this->tlReuPers->contains($tlReuPer)) {
            $this->tlReuPers->removeElement($tlReuPer);
            // set the owning side to null (unless already changed)
            if ($tlReuPer->getIdPersonne() === $this) {
                $tlReuPer->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgMotCleCps[]
     */
    public function getIdMcCps(): Collection
    {
        return $this->idMcCps;
    }

    public function addIdMcCp(TgMotCleCps $idMcCp): self
    {
        if (!$this->idMcCps->contains($idMcCp)) {
            $this->idMcCps[] = $idMcCp;
            $idMcCp->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdMcCp(TgMotCleCps $idMcCp): self
    {
        if ($this->idMcCps->contains($idMcCp)) {
            $this->idMcCps->removeElement($idMcCp);
            // set the owning side to null (unless already changed)
            if ($idMcCp->getIdPersonne() === $this) {
                $idMcCp->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgMotCleCv[]
     */
    public function getIdMcCv(): Collection
    {
        return $this->idMcCv;
    }

    public function addIdMcCv(TgMotCleCv $idMcCv): self
    {
        if (!$this->idMcCv->contains($idMcCv)) {
            $this->idMcCv[] = $idMcCv;
            $idMcCv->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdMcCv(TgMotCleCv $idMcCv): self
    {
        if ($this->idMcCv->contains($idMcCv)) {
            $this->idMcCv->removeElement($idMcCv);
            // set the owning side to null (unless already changed)
            if ($idMcCv->getIdPersonne() === $this) {
                $idMcCv->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgAdrMail[]
     */
    public function getIdAdrMail(): Collection
    {
        return $this->idAdrMail;
    }

    public function addIdAdrMail(TgAdrMail $idAdrMail): self
    {
        if (!$this->idAdrMail->contains($idAdrMail)) {
            $this->idAdrMail[] = $idAdrMail;
            $idAdrMail->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdAdrMail(TgAdrMail $idAdrMail): self
    {
        if ($this->idAdrMail->contains($idAdrMail)) {
            $this->idAdrMail->removeElement($idAdrMail);
            // set the owning side to null (unless already changed)
            if ($idAdrMail->getIdPersonne() === $this) {
                $idAdrMail->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgTelephone[]
     */
    public function getNoTelephone(): Collection
    {
        return $this->noTelephone;
    }

    public function addNoTelephone(TgTelephone $noTelephone): self
    {
        if (!$this->noTelephone->contains($noTelephone)) {
            $this->noTelephone[] = $noTelephone;
            $noTelephone->setIdPersonne($this);
        }

        return $this;
    }

    public function removeNoTelephone(TgTelephone $noTelephone): self
    {
        if ($this->noTelephone->contains($noTelephone)) {
            $this->noTelephone->removeElement($noTelephone);
            // set the owning side to null (unless already changed)
            if ($noTelephone->getIdPersonne() === $this) {
                $noTelephone->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlLangPers[]
     */
    public function getIdLangPers(): Collection
    {
        return $this->idLangPers;
    }

    public function addIdLangPer(TlLangPers $idLangPer): self
    {
        if (!$this->idLangPers->contains($idLangPer)) {
            $this->idLangPers[] = $idLangPer;
            $idLangPer->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdLangPer(TlLangPers $idLangPer): self
    {
        if ($this->idLangPers->contains($idLangPer)) {
            $this->idLangPers->removeElement($idLangPer);
            // set the owning side to null (unless already changed)
            if ($idLangPer->getIdPersonne() === $this) {
                $idLangPer->setIdPersonne(null);
            }
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
            $idHabilitation->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if ($this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation->removeElement($idHabilitation);
            // set the owning side to null (unless already changed)
            if ($idHabilitation->getIdPersonne() === $this) {
                $idHabilitation->setIdPersonne(null);
            }
        }

        return $this;
    }

    public function getCvRenseigne(): ?bool
    {
        return $this->cvRenseigne;
    }

    public function setCvRenseigne(bool $cvRenseigne): self
    {
        $this->cvRenseigne = $cvRenseigne;

        return $this;
    }

    /**
     * @return Collection|TgPartenariat[]
     */
    public function getIdPartenaire(): Collection
    {
        return $this->idPartenaire;
    }

    public function addIdPartenaire(TgPartenariat $idPartenaire): self
    {
        if (!$this->idPartenaire->contains($idPartenaire)) {
            $this->idPartenaire[] = $idPartenaire;
            $idPartenaire->addIdPersonne($this);
        }

        return $this;
    }

    public function removeIdPartenaire(TgPartenariat $idPartenaire): self
    {
        if ($this->idPartenaire->contains($idPartenaire)) {
            $this->idPartenaire->removeElement($idPartenaire);
            $idPartenaire->removeIdPersonne($this);
        }

        return $this;
    }

    public function getIdDispoComite(): ?TrDispoComite
    {
        return $this->idDispoComite;
    }

    public function setIdDispoComite(?TrDispoComite $idDispoComite): self
    {
        $this->idDispoComite = $idDispoComite;

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
        }

        return $this;
    }

    public function removeIdMcErc(TgMotCleErc $idMcErc): self
    {
        if ($this->idMcErc->contains($idMcErc)) {
            $this->idMcErc->removeElement($idMcErc);
        }

        return $this;
    }

    /**
     * @return Collection|TlPersonneMcErc[]
     */
    public function getTlPersonneMcErc(): Collection
    {
        return $this->tlPersonneMcErc;
    }

    public function addTlPersonneMcErc(TlPersonneMcErc $tlPersonneMcErc): self
    {
        if (!$this->tlPersonneMcErc->contains($tlPersonneMcErc)) {
            $this->tlPersonneMcErc[] = $tlPersonneMcErc;
            $tlPersonneMcErc->setIdPersonne($this);
        }

        return $this;
    }

    public function removeTlPersonneMcErc(TlPersonneMcErc $tlPersonneMcErc): self
    {
        if ($this->tlPersonneMcErc->contains($tlPersonneMcErc)) {
            $this->tlPersonneMcErc->removeElement($tlPersonneMcErc);
            // set the owning side to null (unless already changed)
            if ($tlPersonneMcErc->getIdPersonne() === $this) {
                $tlPersonneMcErc->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlPersonneMcLibre[]
     */
    public function getTlPersonneMcLibre(): Collection
    {
        return $this->tlPersonneMcLibre;
    }

    public function addTlPersonneMcLibre(TlPersonneMcLibre $tlPersonneMcLibre): self
    {
        if (!$this->tlPersonneMcLibre->contains($tlPersonneMcLibre)) {
            $this->tlPersonneMcLibre[] = $tlPersonneMcLibre;
            $tlPersonneMcLibre->setIdPersonne($this);
        }

        return $this;
    }

    public function removeTlPersonneMcLibre(TlPersonneMcLibre $tlPersonneMcLibre): self
    {
        if ($this->tlPersonneMcLibre->contains($tlPersonneMcLibre)) {
            $this->tlPersonneMcLibre->removeElement($tlPersonneMcLibre);
            // set the owning side to null (unless already changed)
            if ($tlPersonneMcLibre->getIdPersonne() === $this) {
                $tlPersonneMcLibre->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlPersOrg[]
     */
    public function getTlPersOrg(): Collection
    {
        return $this->tlPersOrg;
    }

    public function addTlPersOrg(TlPersOrg $tlPersOrg): self
    {
        if (!$this->tlPersOrg->contains($tlPersOrg)) {
            $this->tlPersOrg[] = $tlPersOrg;
            $tlPersOrg->setIdPersonne($this);
        }

        return $this;
    }

    public function removeTlPersOrg(TlPersOrg $tlPersOrg): self
    {
        if ($this->tlPersOrg->contains($tlPersOrg)) {
            $this->tlPersOrg->removeElement($tlPersOrg);
            // set the owning side to null (unless already changed)
            if ($tlPersOrg->getIdPersonne() === $this) {
                $tlPersOrg->setIdPersonne(null);
            }
        }

        return $this;
    }

    public function getIdCv(): ?TgCv
    {
        return $this->idCv;
    }

    public function setIdCv(?TgCv $idCv): self
    {
        $this->idCv = $idCv;

        return $this;
    }

    /**
     * @return Collection|TgAdrMailNotification[]
     */
    public function getAdrMailNotif(): Collection
    {
        return $this->adrMailNotif;
    }

    public function addAdrMailNotif(TgAdrMailNotification $adrMailNotif): self
    {
        if (!$this->adrMailNotif->contains($adrMailNotif)) {
            $this->adrMailNotif[] = $adrMailNotif;
            $adrMailNotif->setIdPersonne($this);
        }

        return $this;
    }

    public function removeAdrMailNotif(TgAdrMailNotification $adrMailNotif): self
    {
        if ($this->adrMailNotif->contains($adrMailNotif)) {
            $this->adrMailNotif->removeElement($adrMailNotif);
            // set the owning side to null (unless already changed)
            if ($adrMailNotif->getIdPersonne() === $this) {
                $adrMailNotif->setIdPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgIdExternes[]
     */
    public function getIdIdentExt(): Collection
    {
        return $this->idIdentExt;
    }

    public function addIdIdentExt(TgIdExternes $idIdentExt): self
    {
        if (!$this->idIdentExt->contains($idIdentExt)) {
            $this->idIdentExt[] = $idIdentExt;
            $idIdentExt->setIdPersonne($this);
        }

        return $this;
    }

    public function removeIdIdentExt(TgIdExternes $idIdentExt): self
    {
        if ($this->idIdentExt->contains($idIdentExt)) {
            $this->idIdentExt->removeElement($idIdentExt);
            // set the owning side to null (unless already changed)
            if ($idIdentExt->getIdPersonne() === $this) {
                $idIdentExt->setIdPersonne(null);
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
            $avisProjet->setIdPersonne($this);
        }

        return $this;
    }

    public function removeAvisProjet(TrAvisProjet $avisProjet): self
    {
        if ($this->avisProjet->contains($avisProjet)) {
            $this->avisProjet->removeElement($avisProjet);
            // set the owning side to null (unless already changed)
            if ($avisProjet->getIdPersonne() === $this) {
                $avisProjet->setIdPersonne(null);
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
            $experProj->setIdPersonne($this);
        }

        return $this;
    }

    public function removeExperProj(TlExpertProj $experProj): self
    {
        if ($this->experProj->contains($experProj)) {
            $this->experProj->removeElement($experProj);
            // set the owning side to null (unless already changed)
            if ($experProj->getIdPersonne() === $this) {
                $experProj->setIdPersonne(null);
            }
        }

        return $this;
    }

}
