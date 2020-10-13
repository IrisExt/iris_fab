<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
/**
 * TgCourriel
 *
 * @ORM\Table(name="tg_courriel", uniqueConstraints={@ORM\UniqueConstraint(name="tg_courriel_pk", columns={"id_courriel"})}, indexes={@ORM\Index(name="expediteur_fk", columns={"adr_mail"}), @ORM\Index(name="destinataire_pour_test_fk", columns={"dest_test"}), @ORM\Index(name="a_pour_categorie_courriel_fk", columns={"id_cat_modele"}), @ORM\Index(name="a_pour_modele_fk", columns={"modele"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgCourrielRepository")
 */
class TgCourriel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_courriel", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_courriel_id_courriel_seq", allocationSize=1, initialValue=1)
     */
    private $idCourriel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adr_emetteur", type="string", length=255, nullable=true)
     */
    private $adrEmetteur;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_objet", type="string", length=255, nullable=false)
     */
    private $lbObjet;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_message_fr", type="text", nullable=true)
     */
    private $lbMessageFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_message_en", type="text", nullable=true)
     */
    private $lbMessageEn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dh_creation", type="date", nullable=false)
     */
    private $dhCreation;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_envoi", type="date", nullable=true)
     */
    private $dhEnvoi;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_format", type="string", length=255, nullable=true, options={"comment"="Pr�cise si le mod�le ou le courriel est au format 'html' ou texte..."})
     */
    private $lbFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_courriel", type="string", length=10, nullable=false, options={"fixed"=true,"comment"="Pr�cise si le courriel est unitaire ou un mod�le de courriel"})
     */
    private $typCourriel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_designation", type="string", length=255, nullable=true)
     */
    private $lbDesignation;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_modifiable_cps", type="boolean", nullable=true)
     */
    private $blModifiableCps;

    /**
     * @var \TrCatModele
     *
     * @ORM\ManyToOne(targetEntity="TrCatModele")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_modele", referencedColumnName="id_cat_modele")
     * })
     */
    private $idCatModele;

    /**
     * @var \TgCourriel
     *
     * @ORM\ManyToOne(targetEntity="TgCourriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modele", referencedColumnName="id_courriel")
     * })
     */
    private $modele;

    /**
     * @var \TgAdrMail
     *
     * @ORM\ManyToOne(targetEntity="TgAdrMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dest_test", referencedColumnName="adr_mail")
     * })
     */
    private $destTest;

    /**
     * @var \TgAdrMail
     *
     * @ORM\ManyToOne(targetEntity="TgAdrMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adr_mail", referencedColumnName="adr_mail")
     * })
     */
    private $adrMail;

    /**
     * @var TlCopieMail
     * @ORM\OneToMany(targetEntity="App\Entity\TlCopieMail", mappedBy="idCourriel", cascade={"persist"})
     */
    private $copieMail;

    /**
     * @var TlCourrielDest
     * @ORM\OneToMany(targetEntity="TlCourrielDest", mappedBy="idCourriel", cascade={"persist"})
     */
    private $destMail;


    /**
     * @var TlPjLibre
     * @ORM\OneToMany(targetEntity="App\Entity\TlPjLibre", mappedBy="idCourriel", cascade={"persist"})
     * doc libre
     */
    private $docLibre;

    /**
     * @var TlDocCourriel
     * @ORM\OneToMany(targetEntity="App\Entity\TlDocCourriel", mappedBy="idCourriel", cascade={"persist"})
     * doc libre
     */
    private $docCourriel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgBalise", inversedBy="idCourriel")
     * @ORM\JoinTable(name="tl_modele_balise",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_courriel", referencedColumnName="id_courriel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_balise", referencedColumnName="id_balise")
     *   }
     * )
     */
    private $idBalise;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrTypeDocument", inversedBy="idCourriel")
     * @ORM\JoinTable(name="tl_type_doc_joint",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_courriel", referencedColumnName="id_courriel")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_type_doc", referencedColumnName="id_type_doc")
     *   }
     * )
     */
    private $idTypeDoc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dhCreation = new \DateTime();
        $this->copieMail = new \Doctrine\Common\Collections\ArrayCollection();
        $this->destMail = new \Doctrine\Common\Collections\ArrayCollection();
        $this->docCourriel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idBalise = new \Doctrine\Common\Collections\ArrayCollection();
        $this->docLibre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idTypeDoc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdCourriel(): int
    {
        return $this->idCourriel;
    }

    /**
     * @param int $idCourriel
     */
    public function setIdCourriel(int $idCourriel): void
    {
        $this->idCourriel = $idCourriel;
    }

    /**
     * @return string|null
     */
    public function getAdrEmetteur(): ?string
    {
        return $this->adrEmetteur;
    }

    /**
     * @param string|null $adrEmetteur
     */
    public function setAdrEmetteur(?string $adrEmetteur): void
    {
        $this->adrEmetteur = $adrEmetteur;
    }

    /**
     * @return string
     */
    public function getLbObjet(): ?string
    {
        return $this->lbObjet;
    }

    /**
     * @param string $lbObjet
     */
    public function setLbObjet(string $lbObjet): void
    {
        $this->lbObjet = $lbObjet;
    }

    /**
     * @return string|null
     */
    public function getLbMessageFr(): ?string
    {
        return $this->lbMessageFr;
    }

    /**
     * @param string|null $lbMessageFr
     */
    public function setLbMessageFr(?string $lbMessageFr): void
    {
        $this->lbMessageFr = $lbMessageFr;
    }

    /**
     * @return string|null
     */
    public function getLbMessageEn(): ?string
    {
        return $this->lbMessageEn;
    }

    /**
     * @param string|null $lbMessageEn
     */
    public function setLbMessageEn(?string $lbMessageEn): void
    {
        $this->lbMessageEn = $lbMessageEn;
    }

    /**
     * @return \DateTime
     */
    public function getDhCreation(): ?\DateTime
    {
        return $this->dhCreation;
    }

    /**
     * @param \DateTime $dhCreation
     */
    public function setDhCreation(\DateTime $dhCreation): void
    {
        $this->dhCreation = $dhCreation;
    }

    /**
     * @return \DateTime|null
     */
    public function getDhEnvoi(): ?\DateTime
    {
        return $this->dhEnvoi;
    }

    /**
     * @param \DateTime|null $dhEnvoi
     */
    public function setDhEnvoi(?\DateTime $dhEnvoi): void
    {
        $this->dhEnvoi = $dhEnvoi;
    }

    /**
     * @return string|null
     */
    public function getLbFormat(): ?string
    {
        return $this->lbFormat;
    }

    /**
     * @param string|null $lbFormat
     */
    public function setLbFormat(?string $lbFormat): void
    {
        $this->lbFormat = $lbFormat;
    }

    /**
     * @return string
     */
    public function getTypCourriel(): ?string
    {
        return $this->typCourriel;
    }

    /**
     * @param string $typCourriel
     */
    public function setTypCourriel(string $typCourriel): void
    {
        $this->typCourriel = $typCourriel;
    }

    /**
     * @return string|null
     */
    public function getLbDesignation(): ?string
    {
        return $this->lbDesignation;
    }

    /**
     * @param string|null $lbDesignation
     */
    public function setLbDesignation(?string $lbDesignation): void
    {
        $this->lbDesignation = $lbDesignation;
    }

    /**
     * @return bool|null
     */
    public function getBlModifiableCps(): ?bool
    {
        return $this->blModifiableCps;
    }

    /**
     * @param bool|null $blModifiableCps
     */
    public function setBlModifiableCps(?bool $blModifiableCps): void
    {
        $this->blModifiableCps = $blModifiableCps;
    }

    /**
     * @return \TrCatModele
     */
    public function getIdCatModele(): ?TrCatModele
    {
        return $this->idCatModele;
    }

    /**
     * @param \TrCatModele $idCatModele
     */
    public function setIdCatModele(?TrCatModele $idCatModele): void
    {
        $this->idCatModele = $idCatModele;
    }

    /**
     * @return \TgCourriel
     */
    public function getModele(): ?TgCourriel
    {
        return $this->modele;
    }

    /**
     * @param \TgCourriel $modele
     */
    public function setModele(?TgCourriel $modele): void
    {
        $this->modele = $modele;
    }

    /**
     * @return \TgAdrMail
     */
    public function getDestTest(): ?TgAdrMail
    {
        return $this->destTest;
    }

    /**
     * @param \TgAdrMail $destTest
     */
    public function setDestTest(?TgAdrMail $destTest): void
    {
        $this->destTest = $destTest;
    }

    /**
     * @return \TgAdrMail
     */
    public function getAdrMail(): ?TgAdrMail
    {
        return $this->adrMail;
    }

    /**
     * @param TgAdrMail $adrMail
     */
    public function setAdrMail(?TgAdrMail $adrMail): void
    {
        $this->adrMail = $adrMail;
    }



    /**
     * @return TlCourrielDest
     */
    public function getDestMail(): ?TlCourrielDest
    {
        return $this->destMail;
    }

    /**
     * @param TlCourrielDest $destMail
     */
    public function setDestMail(?TlCourrielDest $destMail): void
    {
        $this->destMail = $destMail;
    }

    /**
     * @return TlPjLibre
     */
    public function getDocLibre(): ?TlPjLibre
    {
        return $this->docLibre;
    }

    /**
     * @param TlPjLibre $docLibre
     */
    public function setDocLibre(?TlPjLibre $docLibre): void
    {
        $this->docLibre = $docLibre;
    }

    /**
     * @return TlDocCourriel
     */
    public function getDocCourriel(): ?TlDocCourriel
    {
        return $this->docCourriel;
    }

    /**
     * @param TlDocCourriel $docCourriel
     */
    public function setDocCourriel(?TlDocCourriel $docCourriel): void
    {
        $this->docCourriel = $docCourriel;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdBalise(): \Doctrine\Common\Collections\Collection
    {
        return $this->idBalise;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idBalise
     */
    public function setIdBalise(\Doctrine\Common\Collections\Collection $idBalise): void
    {
        $this->idBalise = $idBalise;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdTypeDoc(): \Doctrine\Common\Collections\Collection
    {
        return $this->idTypeDoc;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idTypeDoc
     */
    public function setIdTypeDoc(\Doctrine\Common\Collections\Collection $idTypeDoc): void
    {
        $this->idTypeDoc = $idTypeDoc;
    }

//    /**
//     * @return TlCopieMail
//     */
//    public function getCopieMail(): TlCopieMail
//    {
//        return $this->copieMail;
//    }

//    /**
//     * @param TlCopieMail $copieMail
//     */
//    public function setCopieMail(TlCopieMail $copieMail): void
//    {
//        $this->copieMail = $copieMail;
//    }

    /**
     * @return Collection|TlCopieMail[]
     */
    public function getCopieMail(): Collection
    {
        return $this->copieMail;
    }

    public function addCopieMail(TlCopieMail $copieMail): self
    {
        if (!$this->copieMail->contains($copieMail)) {
            $this->copieMail[] = $copieMail;
            $copieMail->setAdrMail($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbDesignation();
    }
}
