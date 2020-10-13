<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgOrganisme
 *
 * @ORM\Table(name="tg_organisme", uniqueConstraints={@ORM\UniqueConstraint(name="tg_organisme_pk", columns={"id_organisme"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgOrganismeRepository")
 */
class TgOrganisme
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_organisme", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_organisme_id_organisme_seq", allocationSize=1, initialValue=1)
     */
    private $idOrganisme;

    /**
     * @var \TrRnsr
     *
     * @ORM\ManyToOne(targetEntity="TrRnsr", inversedBy="idOrganisme", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_rnsr", referencedColumnName="cd_rnsr")
     * })
     *
     * @Assert\NotBlank(
     *     message="tgorganisme.cd_rnsr.not_blank",
     *     groups={"PartenaireType"}
     *
     * )
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *      groups={"PartenaireType"}
     * )
     *
     */
    private $cdRnsr;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="tgorganisme.lbnom.not_blank",
     *     groups={"PartenaireType", "PartenaireEtr", "PartenaireTypePrf"}
     *
     * )
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNomFr;


    /**
     * @var \TrSiret
     *
     * @ORM\ManyToOne(targetEntity="TrSiret", inversedBy="idOrganisme", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="siret", referencedColumnName="siret")
     * })
     * @Assert\NotBlank(
     *     message="tgorganisme.siret.not_blank",
     *     groups={"PartenaireType", "PartenaireTypePrf"}
     *
     * )
     * @Assert\Length(
     *      min = 14,
     *      max = 14,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *      minMessage = "Le titire est limité à {{ limit }} caractères",
     * )
     */
    private $siret;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_service", type="string", length=50, nullable=true)
     */
    private $lbService;

    /**
     * @var TlPersonneMclibre
     * @ORM\OneToMany(targetEntity="App\Entity\TlPersOrg", mappedBy="idOrganisme", cascade={"persist"})
     */
    private $tlPersOrg;

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
     * @var TrPays
     *
     * @ORM\ManyToOne(targetEntity="TrPays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_pays", referencedColumnName="cd_pays")
     * })
     * @Assert\NotBlank(
     *     message="tgorganisme.cdPays.not_blank",
     *     groups={"PartenaireEtr"}
     *
     * )
     */
    private $cdPays;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_laboratoire", type="string", length=150, nullable=true)
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "Le nom du Laboratoire est limité à {{ limit }} caractères")
     */
    private $lbLaboratoire;


    public function __construct()
    {
        $this->tlPersOrg = new ArrayCollection();
    }

    public function setIdOrganisme(int $idOrganisme): self
    {
        $this->idOrganisme = $idOrganisme;
        return $this;
    }

    public function getIdOrganisme(): ?int
    {
        return $this->idOrganisme;
    }

    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    public function setLbNomFr(?string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;

        return $this;
    }


    public function getLbService(): ?string
    {
        return $this->lbService;
    }

    public function setLbService(?string $lbService): self
    {
        $this->lbService = $lbService;

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
            $tlPersOrg->setIdOrganisme($this);
        }

        return $this;
    }

    public function removeTlPersOrg(TlPersOrg $tlPersOrg): self
    {
        if ($this->tlPersOrg->contains($tlPersOrg)) {
            $this->tlPersOrg->removeElement($tlPersOrg);
            // set the owning side to null (unless already changed)
            if ($tlPersOrg->getIdOrganisme() === $this) {
                $tlPersOrg->setIdOrganisme(null);
            }
        }

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

    public function __toString(): string
    {
        return $this->getLbNomFr();
    }

    public function getCdPays(): ?TrPays
    {
        return $this->cdPays;
    }

    /**
     * @param TrPays|null $cdPays
     * @return $this
     */
    public function setCdPays(?TrPays $cdPays): self
    {
        $this->cdPays = $cdPays;

        return $this;
    }




    /**
     * @return string|null
     */
    public function getLbLaboratoire(): ?string
    {
        return $this->lbLaboratoire;
    }

    /**
     * @param string|null $lbLaboratoire
     */
    public function setLbLaboratoire(?string $lbLaboratoire): void
    {
        $this->lbLaboratoire = $lbLaboratoire;
    }

    /**
     * @return TrRnsr
     */
    public function getCdRnsr(): ?TrRnsr
    {
        return $this->cdRnsr;
    }

    /**
     * @param TrRnsr $cdRnsr
     */
    public function setCdRnsr(TrRnsr $cdRnsr): void
    {
        $this->cdRnsr = $cdRnsr;
    }

    /**
     * @return TrSiret
     */
    public function getSiret(): ?TrSiret
    {
        return $this->siret;
    }

    /**
     * @param TrSiret $siret
     */
    public function setSiret(TrSiret $siret): void
    {
        $this->siret = $siret;
    }

}
