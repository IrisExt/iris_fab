<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TgAdresse
 *
 * @ORM\Table(name="tg_adresse", uniqueConstraints={@ORM\UniqueConstraint(name="tg_adresse_pk", columns={"id_adresse"})}, indexes={@ORM\Index(name="adr_pays_fk", columns={"cd_pays"})})
 * @ORM\Entity
 */
class TgAdresse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_adresse", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_adresse_id_adresse_seq", allocationSize=1, initialValue=1)
     */
    private $idAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_adresse", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_compl_adresses", type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbComplAdresses;

    /**
     * @var string
     *
     * @ORM\Column(name="cd", type="string", length=10, nullable=true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $cd;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $ville;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_adr_princ", type="boolean", nullable=true)
     */
    private $blAdrPrinc;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_adresse_pro", type="boolean", nullable=true)
     */
    private $blAdressePro;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_adr", type="string", length=3, nullable=true)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $typAdr;

    /**
     * @var \TrPays
     *
     * @ORM\ManyToOne(targetEntity="TrPays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_pays", referencedColumnName="cd_pays")
     * })
     */
    private $cdPays;



    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne" , inversedBy="idAdresse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var TrRnsr
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrRnsr" , mappedBy="idAdresse")
     */
    private $cdRnsr;

    /**
     * @var TrSiret
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TrSiret" , mappedBy="idAdresse")
     */
    private $siret;

    public function __construct()
    {
        $this->idOrganisme = new ArrayCollection();
    }


    public function getIdAdresse(): ?int
    {
        return $this->idAdresse;
    }

    public function getLbAdresse(): ?string
    {
        return $this->lbAdresse;
    }

    public function setLbAdresse(?string $lbAdresse): self
    {
        $this->lbAdresse = $lbAdresse;

        return $this;
    }

    public function getLbComplAdresses(): ?string
    {
        return $this->lbComplAdresses;
    }

    public function setLbComplAdresses(?string $lbComplAdresses): self
    {
        $this->lbComplAdresses = $lbComplAdresses;

        return $this;
    }

    public function getCd(): ?string
    {
        return $this->cd;
    }

    public function setCd(?string $cd): self
    {
        $this->cd = $cd;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getBlAdrPrinc(): ?bool
    {
        return $this->blAdrPrinc;
    }

    public function setBlAdrPrinc(?bool $blAdrPrinc): self
    {
        $this->blAdrPrinc = $blAdrPrinc;

        return $this;
    }

    public function getTypAdr(): ?string
    {
        return $this->typAdr;
    }

    public function setTypAdr(?string $typAdr): self
    {
        $this->typAdr = $typAdr;

        return $this;
    }

    public function getCdPays(): ?TrPays
    {
        return $this->cdPays;
    }

    public function setCdPays(?TrPays $cdPays): self
    {
        $this->cdPays = $cdPays;

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

    public function __toString()
    {
        RETURN $this->getLbAdresse().' '.$this->getLbComplAdresses(). ' '. $this->getVille();
    }

    /**
     * @return Collection|TrRnsr[]
     */
    public function getCdRnsr(): Collection
    {
        return $this->cdRnsr;
    }

    public function addCdRnsr(TrRnsr $cdRnsr): self
    {
        if (!$this->cdRnsr->contains($cdRnsr)) {
            $this->cdRnsr[] = $cdRnsr;
            $cdRnsr->setIdAdresse($this);
        }

        return $this;
    }

    public function removeCdRnsr(TrRnsr $cdRnsr): self
    {
        if ($this->cdRnsr->contains($cdRnsr)) {
            $this->cdRnsr->removeElement($cdRnsr);
            // set the owning side to null (unless already changed)
            if ($cdRnsr->getIdAdresse() === $this) {
                $cdRnsr->setIdAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrSiret[]
     */
    public function getSiret(): Collection
    {
        return $this->siret;
    }

    public function addSiret(TrSiret $siret): self
    {
        if (!$this->siret->contains($siret)) {
            $this->siret[] = $siret;
            $siret->setIdAdresse($this);
        }

        return $this;
    }

    public function removeSiret(TrSiret $siret): self
    {
        if ($this->siret->contains($siret)) {
            $this->siret->removeElement($siret);
            // set the owning side to null (unless already changed)
            if ($siret->getIdAdresse() === $this) {
                $siret->setIdAdresse(null);
            }
        }

        return $this;
    }
}
