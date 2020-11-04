<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMotCleErc
 *
 * @ORM\Table(name="tg_mot_cle_erc", indexes={@ORM\Index(name="IDX_588F75E3892DBD0", columns={"id_disc_erc"})})
 * @ORM\Entity
 */
class TgMotCleErc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_erc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mot_cle_erc_id_mc_erc_seq", allocationSize=1, initialValue=1)
     */
    private $idMcErc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=255, nullable=false)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNomFr;

    /**
     * @var TrDiscErc
     *
     * @ORM\ManyToOne(targetEntity="TrDiscErc", inversedBy="TgMcErcs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_disc_erc", referencedColumnName="id_disc_erc")
     * })
     */
    private $trMcErcs;

    /**
     * @var TlMcErcAppel
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TlMcErcAppel", mappedBy="idMcErc", cascade={"persist"})
     */
    private $tlMcErcAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idMcErc")
     */
    private $idProjet;

    /**
     * @var TlPersonneMcErc
     * @ORM\OneToMany(targetEntity="TlPersonneMcErc", mappedBy="idMcErc", cascade={"persist"})
     */
    private $tlPersonneMcErc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAppel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tlPersonneMcErc = new ArrayCollection();
        $this->tlMcErcAppel = new ArrayCollection();
    }

    public function getIdMcErc(): ?int
    {
        return $this->idMcErc;
    }

    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    public function setLbNomFr(string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;

        return $this;
    }

    public function getIdDiscErc(): ?TrDiscErc
    {
        return $this->idDiscErc;
    }

    public function setIdDiscErc(?TrDiscErc $idDiscErc): self
    {
        $this->idDiscErc = $idDiscErc;

        return $this;
    }

    /**
     * @return Collection|TgAppelProj[]
     */
    public function getIdAppel(): Collection
    {
        return $this->idAppel;
    }

    public function addIdAppel(TgAppelProj $idAppel): self
    {
        if (!$this->idAppel->contains($idAppel)) {
            $this->idAppel[] = $idAppel;
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
        }

        return $this;
    }

    /**
     * @return Collection|TgProjet[]
     */
    public function getIdProjet(): Collection
    {
        return $this->idProjet;
    }

    public function addIdProjet(TgProjet $idProjet): self
    {
        if (!$this->idProjet->contains($idProjet)) {
            $this->idProjet[] = $idProjet;
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
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
            $tlPersonneMcErc->setIdMcErc($this);
        }

        return $this;
    }

    public function removeTlPersonneMcErc(TlPersonneMcErc $tlPersonneMcErc): self
    {
        if ($this->tlPersonneMcErc->contains($tlPersonneMcErc)) {
            $this->tlPersonneMcErc->removeElement($tlPersonneMcErc);
            // set the owning side to null (unless already changed)
            if ($tlPersonneMcErc->getIdMcErc() === $this) {
                $tlPersonneMcErc->setIdMcErc(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getLbNomFr();
    }

    public function getTrMcErcs(): ?TrDiscErc
    {
        return $this->trMcErcs;
    }

    public function setTrMcErcs(?TrDiscErc $trMcErcs): self
    {
        $this->trMcErcs = $trMcErcs;

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
            $tlMcErcAppel->setIdMcErc($this);
        }

        return $this;
    }

    public function removeTlMcErcAppel(TlMcErcAppel $tlMcErcAppel): self
    {
        if ($this->tlMcErcAppel->contains($tlMcErcAppel)) {
            $this->tlMcErcAppel->removeElement($tlMcErcAppel);
            // set the owning side to null (unless already changed)
            if ($tlMcErcAppel->getIdMcErc() === $this) {
                $tlMcErcAppel->setIdMcErc(null);
            }
        }

        return $this;
    }
}
