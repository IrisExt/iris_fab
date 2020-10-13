<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgFinanceur
 *
 * @ORM\Table(name="tg_financeur", indexes={@ORM\Index(name="IDX_385B8FCA1BCF7FE2", columns={"cd_pays"})})
 * @ORM\Entity
 */
class TgFinanceur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_financeur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_financeur_id_financeur_seq", allocationSize=1, initialValue=1)
     */
    private $idFinanceur;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNomFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cod_fi", type="string", length=3, nullable=true)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $codFi;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgComite", inversedBy="idFinanceur")
     * @ORM\JoinTable(name="tl_comite_agence",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_financeur", referencedColumnName="id_financeur")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     *   }
     * )
     */
    private $idComite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idFinanceur")
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idComite = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdFinanceur(): ?int
    {
        return $this->idFinanceur;
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

    public function getCodFi(): ?string
    {
        return $this->codFi;
    }

    public function setCodFi(?string $codFi): self
    {
        $this->codFi = $codFi;

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

    /**
     * @return Collection|TgComite[]
     */
    public function getIdComite(): Collection
    {
        return $this->idComite;
    }

    public function addIdComite(TgComite $idComite): self
    {
        if (!$this->idComite->contains($idComite)) {
            $this->idComite[] = $idComite;
        }

        return $this;
    }

    public function removeIdComite(TgComite $idComite): self
    {
        if ($this->idComite->contains($idComite)) {
            $this->idComite->removeElement($idComite);
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
            $idProjet->addIdFinanceur($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdFinanceur($this);
        }

        return $this;
    }

}
