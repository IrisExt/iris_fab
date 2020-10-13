<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMcLibre
 *
 * @ORM\Table(name="tg_mc_libre", uniqueConstraints={@ORM\UniqueConstraint(name="tg_mc_libre_pk", columns={"id_mc_libre"})}, indexes={@ORM\Index(name="est_associe_a_projet_fk", columns={"id_projet"})})
 * @ORM\Entity
 */
class TgMcLibre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_libre", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mc_libre_id_mc_libre_seq", allocationSize=1, initialValue=1)
     */
    private $idMcLibre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.mclibrefr.not_blank",
     *     groups={"BlMotCleLibreType"}
     *
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.mclibrefr.min_length",
     *      maxMessage = "tgprojet.mclibrefr.max_length",
     *      groups={"BlMotCleLibreType"}
     * )
     */
    private $lbNom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=true)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.mclibreen.not_blank",
     *     groups={"BlMotCleLibreType"}
     *
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.mclibreen.min_length",
     *      maxMessage = "tgprojet.mclibreen.max_length",
     *     groups={"BlMotCleLibreType"}
     * )
     */
    private $lbNomEn;

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
     * @var TlPersonneMclibre
     * @ORM\OneToMany(targetEntity="TlPersonneMcLibre", mappedBy="idMcLibre", cascade={"persist"})
     */
    private $tlPersonneMcLibre;

    public function __construct()
    {
        $this->tlPersonneMcLibre = new ArrayCollection();
    }


    public function getIdMcLibre(): ?int
    {
        return $this->idMcLibre;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    public function getLbNomEn(): ?string
    {
        return $this->lbNomEn;
    }

    public function setLbNomEn(?string $lbNomEn): self
    {
        $this->lbNomEn = $lbNomEn;

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
            $tlPersonneMcLibre->setIdMcLibre($this);
        }

        return $this;
    }

    public function removeTlPersonneMcLibre(TlPersonneMcLibre $tlPersonneMcLibre): self
    {
        if ($this->tlPersonneMcLibre->contains($tlPersonneMcLibre)) {
            $this->tlPersonneMcLibre->removeElement($tlPersonneMcLibre);
            // set the owning side to null (unless already changed)
            if ($tlPersonneMcLibre->getIdMcLibre() === $this) {
                $tlPersonneMcLibre->setIdMcLibre(null);
            }
        }

        return $this;
    }


}
