<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMcCes
 *
 * @ORM\Table(name="tg_mc_ces", indexes={@ORM\Index(name="IDX_2674727BFA367F72", columns={"id_comite"})})
 * @ORM\Entity
 */
class TgMcCes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_ces", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mc_ces_id_mc_ces_seq", allocationSize=1, initialValue=1)
     */
    private $idMcCes;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_mc_ces", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbMcCes;

    /**
     * @var \TgComite
     *
     * @ORM\ManyToOne(targetEntity="TgComite", inversedBy="idMcCes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     */
    private $idComite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idMcCes")
     *
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdMcCes(): ?int
    {
        return $this->idMcCes;
    }

    public function getLbMcCes(): ?string
    {
        return $this->lbMcCes;
    }

    public function setLbMcCes(string $lbMcCes): self
    {
        $this->lbMcCes = $lbMcCes;

        return $this;
    }

    public function getIdComite(): ?TgComite
    {
        return $this->idComite;
    }

    public function setIdComite(?TgComite $idComite): self
    {
        $this->idComite = $idComite;

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

    public function __toString()
    {
        return $this->getLbMcCes();
    }
}
