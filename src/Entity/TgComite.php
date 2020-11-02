<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgComite
 *
 * @ORM\Table(name="tg_comite", uniqueConstraints={@ORM\UniqueConstraint(name="tg_comite_pk", columns={"id_comite"})}, indexes={@ORM\Index(name="est_constitue_dans_le_cadre_fk", columns={"id_appel"})})
 * @ORM\Entity(repositoryClass="App\Repository\ComiteRepository")
 */
class TgComite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_comite", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_comite_id_comite_seq", allocationSize=1, initialValue=1)
     */
    private $idComite;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="lb_acr", type="string", length=10, nullable=false, options={"comment"="acronyme , Code unique du comité ex: CE01, CE02 "})
     * @Assert\NotBlank(
     *     message="tgcomite.lbacr.not_blank",
     *
     * )
     * @Assert\Length(
     *      max = 10,
     *      min = 3,
     *      maxMessage = "tgcomite.lbacr.max_length",
     *      minMessage = "tgcomite.lbacr.min_length"
     * )
     */
    private $lbAcr;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_titre", type="string", length=255, nullable=false, options={"comment"="Titre du comité"})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbTitre;

    /**
     * @var int
     *
     * @ORM\Column(name="bl_actif", type="bigint", nullable=false, options={"comment"="Précise si le comité est actif ou supprimé (valeur 0)"})
     */
    private $blActif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_desc", type="string", length=1000, nullable=true, options={"comment"="Description du contenu du comité"})
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     */
    private $lbDesc;

    /**
     * @var TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrDepartement", inversedBy="idComite",  cascade={"persist"})
     * @ORM\JoinTable(name="tl_comite_dep",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_departement", referencedColumnName="id_departement")
     *   }
     * )
     */
    private $idDepartement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgMessage", mappedBy="idComite", cascade={"persist"})
     */
    private $idMessage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgFinanceur", mappedBy="idComite")
     */
    private $idFinanceur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgSeance", mappedBy="idComite", cascade={"persist"})
     */
    private $idSeance;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgHabilitation", inversedBy="idComite")
     * @ORM\JoinTable(name="tl_hab_comite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_habilitation", referencedColumnName="id_habilitation")
     *   }
     * )
     */
    private $idHabilitation;

    /**
     * @var TlAvisPossibles
     * @ORM\OneToMany(targetEntity="TlAvisPossibles", mappedBy="idComite", cascade={"persist"})
     */
    private $avisPossibles;

    /**
     * @var bool
     *
     * @ORM\Column(name="quest_publie", type="boolean", nullable=false, options={"default":false})
     */
    private $questPublie = false;

    /**
     * @var int
     * @ORM\Column(name="nb_quest_soum", type="bigint", nullable=false, options={"default" : 0})
     */
    private $nbQuestSoum = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="bl_droit_projet_ouvert", type="boolean", nullable=false, options={"default":false})
     */
    private $blDroitProjetOuvert = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_min_eval_soum", type="smallint", nullable=true, options={"comment"="Nombre minimal d'évaluations soumises par projet"})
     */
    private $nbMinEvalSoum;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_min_eval_accept", type="smallint", nullable=true, options={"comment"="Nombre minimum d'évaluations acceptées par projet"})
     */
    private $nbMinEvalAccept;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_rendu_eval_rl", type="date", nullable=true)
     */
    private $dhRenduEvalRl;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_rendu_rapport", type="date", nullable=true)
     */
    private $dhRenduRapport;

    /**
     * @var TrLangue
     *
     * @ORM\ManyToOne(targetEntity="TrLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    /**
     * @ORM\OneToMany(targetEntity="TgMcCes", mappedBy="idComite")
     */
    private $idMcCes;


    public function __construct()
    {
        $this->idDepartement = new ArrayCollection();
        $this->idMessage = new ArrayCollection();
        $this->idFinanceur = new ArrayCollection();
        $this->idHabilitation = new ArrayCollection();
        $this->idSeance = new ArrayCollection();
        $this->avisPossibles = new ArrayCollection();
        $this->idMcCes = new ArrayCollection();
    }



    public function __toString(): string
    {
        return $this->getLbAcr();
    }

    public function getIdComite(): ?int
    {
        return $this->idComite;
    }

    public function getLbAcr(): ?string
    {
        return $this->lbAcr;
    }

    public function setLbAcr(string $lbAcr): self
    {
        $this->lbAcr = $lbAcr;

        return $this;
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

    public function getBlActif(): ?int
    {
        return $this->blActif;
    }

    public function setBlActif(int $blActif): self
    {
        $this->blActif = $blActif;

        return $this;
    }

    public function getLbDesc(): ?string
    {
        return $this->lbDesc;
    }

    public function setLbDesc(?string $lbDesc): self
    {
        $this->lbDesc = $lbDesc;

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
     * @return Collection|TrDepartement[]
     */
    public function getIdDepartement(): Collection
    {
        return $this->idDepartement;
    }

    public function addIdDepartement(TrDepartement $idDepartement): self
    {
        if (!$this->idDepartement->contains($idDepartement)) {
            $this->idDepartement[] = $idDepartement;
        }

        return $this;
    }

    public function removeIdDepartement(TrDepartement $idDepartement): self
    {
        if ($this->idDepartement->contains($idDepartement)) {
            $this->idDepartement->removeElement($idDepartement);
        }

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
            $idMessage->setIdComite($this);
        }

        return $this;
    }

    public function removeIdMessage(TgMessage $idMessage): self
    {
        if ($this->idMessage->contains($idMessage)) {
            $this->idMessage->removeElement($idMessage);
            // set the owning side to null (unless already changed)
            if ($idMessage->getIdComite() === $this) {
                $idMessage->setIdComite(null);
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
            $idFinanceur->addIdComite($this);
        }

        return $this;
    }

    public function removeIdFinanceur(TgFinanceur $idFinanceur): self
    {
        if ($this->idFinanceur->contains($idFinanceur)) {
            $this->idFinanceur->removeElement($idFinanceur);
            $idFinanceur->removeIdComite($this);
        }

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
            $idSeance->setIdComite($this);
        }

        return $this;
    }

    public function removeIdSeance(TgSeance $idSeance): self
    {
        if ($this->idSeance->contains($idSeance)) {
            $this->idSeance->removeElement($idSeance);
            // set the owning side to null (unless already changed)
            if ($idSeance->getIdComite() === $this) {
                $idSeance->setIdComite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TlAvisPossibles[]
     */
    public function getAvisPossibles(): Collection
    {
        return $this->avisPossibles;
    }

    public function addAvisPossibles(TlAvisPossibles $avisPossibles): self
    {
        if (!$this->avisPossibles->contains($avisPossibles)) {
            $this->avisPossibles[] = $avisPossibles;
            $avisPossibles->setIdComite($this);
        }

        return $this;
    }

    public function removeAvisPossibles(TlAvisPossibles $avisPossibles): self
    {
        if ($this->avisPossibles->contains($avisPossibles)) {
            $this->avisPossibles->removeElement($avisPossibles);
            // set the owning side to null (unless already changed)
            if ($avisPossibles->getIdComite() === $this) {
                $avisPossibles->setIdComite(null);
            }
        }

        return $this;
    }

    public function getQuestPublie(): ?bool
    {
        return $this->questPublie;
    }

    public function setQuestPublie(?bool $questPublie): self
    {
        $this->questPublie = $questPublie;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbQuestSoum(): int
    {
        return $this->nbQuestSoum;
    }

    /**
     * @param int $nbQuestSoum
     */
    public function setNbQuestSoum(int $nbQuestSoum): void
    {
        $this->nbQuestSoum = $nbQuestSoum;
    }

    public function addAvisPossible(TlAvisPossibles $avisPossible): self
    {
        if (!$this->avisPossibles->contains($avisPossible)) {
            $this->avisPossibles[] = $avisPossible;
            $avisPossible->setIdComite($this);
        }

        return $this;
    }

    public function removeAvisPossible(TlAvisPossibles $avisPossible): self
    {
        if ($this->avisPossibles->contains($avisPossible)) {
            $this->avisPossibles->removeElement($avisPossible);
            // set the owning side to null (unless already changed)
            if ($avisPossible->getIdComite() === $this) {
                $avisPossible->setIdComite(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlDroitProjetOuvert(): bool
    {
        return $this->blDroitProjetOuvert;
    }

    /**
     * @param bool $blDroitProjetOuvert
     */
    public function setBlDroitProjetOuvert(bool $blDroitProjetOuvert): void
    {
        $this->blDroitProjetOuvert = $blDroitProjetOuvert;
    }

    /**
     * @return \TrLangue
     */
    public function getIdLangue(): ?TrLangue
    {
        return $this->idLangue;
    }

    /**
     * @param TrLangue $idLangue
     */
    public function setIdLangue(?TrLangue $idLangue): void
    {
        $this->idLangue = $idLangue;
    }

    /**
     * @return \DateTime|null
     */
    public function getDhRenduEvalRl(): ?\DateTime
    {
        return $this->dhRenduEvalRl;
    }

    /**
     * @param \DateTime|null $dhRenduEvalRl
     */
    public function setDhRenduEvalRl(?\DateTime $dhRenduEvalRl): void
    {
        $this->dhRenduEvalRl = $dhRenduEvalRl;
    }

    /**
     * @return \DateTime|null
     */
    public function getDhRenduRapport(): ?\DateTime
    {
        return $this->dhRenduRapport;
    }

    /**
     * @param \DateTime|null $dhRenduRapport
     */
    public function setDhRenduRapport(?\DateTime $dhRenduRapport): void
    {
        $this->dhRenduRapport = $dhRenduRapport;
    }

    /**
     * @return int|null
     */
    public function getNbMinEvalAccept(): ?int
    {
        return $this->nbMinEvalAccept;
    }

    /**
     * @param int|null $nbMinEvalAccept
     */
    public function setNbMinEvalAccept(?int $nbMinEvalAccept): void
    {
        $this->nbMinEvalAccept = $nbMinEvalAccept;
    }

    /**
     * @return int|null
     */
    public function getNbMinEvalSoum(): ?int
    {
        return $this->nbMinEvalSoum;
    }

    /**
     * @param int|null $nbMinEvalSoum
     */
    public function setNbMinEvalSoum(?int $nbMinEvalSoum): void
    {
        $this->nbMinEvalSoum = $nbMinEvalSoum;
    }

    public function getBlDroitProjetOuvert(): ?bool
    {
        return $this->blDroitProjetOuvert;
    }

    /**
     * @return Collection|TgMcCes[]
     */
    public function getIdMcCes(): Collection
    {
        return $this->idMcCes;
    }

    public function addIdMcCe(TgMcCes $idMcCe): self
    {
        if (!$this->idMcCes->contains($idMcCe)) {
            $this->idMcCes[] = $idMcCe;
            $idMcCe->setTgComite($this);
        }

        return $this;
    }

    public function removeIdMcCe(TgMcCes $idMcCe): self
    {
        if ($this->idMcCes->contains($idMcCe)) {
            $this->idMcCes->removeElement($idMcCe);
            // set the owning side to null (unless already changed)
            if ($idMcCe->getTgComite() === $this) {
                $idMcCe->setTgComite(null);
            }
        }

        return $this;
    }

}
