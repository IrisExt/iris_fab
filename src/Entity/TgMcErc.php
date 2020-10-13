<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMcErc
 *
 * @ORM\Table(name="tg_mc_erc", uniqueConstraints={@ORM\UniqueConstraint(name="mot_cle_erc_pk", columns={"id_mc_erc"})}, indexes={@ORM\Index(name="mot_cle_fait_partie_fk", columns={"id_disc_erc"}), @ORM\Index(name="a_pour_millesime_fk", columns={"id_appel"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgMcErcRepository")
 */
class TgMcErc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_erc", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mc_erc_id_mc_erc_seq", allocationSize=1, initialValue=1)
     */
    private $idMcErc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_mc_erc", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbMcErc;

    /**
     * @var \TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    /**
     * @var \TrDiscErc
     *
     * @ORM\ManyToOne(targetEntity="TrDiscErc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_disc_erc", referencedColumnName="id_disc_erc")
     * })
     */
    private $TrDiscErc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idMcErc")
     */
    private $idProjet;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();

    }

    public function getIdMcErc(): ?int
    {
        return $this->idMcErc;
    }

    public function getLbMcErc(): ?string
    {
        return $this->lbMcErc;
    }

    public function setLbMcErc(string $lbMcErc): self
    {
        $this->lbMcErc = $lbMcErc;

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
            $idProjet->addIdMcErc($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdMcErc($this);
        }

        return $this;
    }

    public function getTrDiscErc(): ?TrDiscErc
    {
        return $this->TrDiscErc;
    }

    public function setTrDiscErc(?TrDiscErc $TrDiscErc): self
    {
        $this->TrDiscErc = $TrDiscErc;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbMcErc();
    }


}
