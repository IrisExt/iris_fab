<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgSeance
 *
 * @ORM\Table(name="tg_seance", uniqueConstraints={@ORM\UniqueConstraint(name="date_reunion_pk", columns={"id_seance"})} )
 * @ORM\Entity(repositoryClass="App\Repository\SeanceRepository")
 */
class TgSeance
{
    /**
     * @var int
     * @ORM\Column(name="id_seance", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_seance_id_seance_seq", allocationSize=1, initialValue=1)
     */
    private $idSeance;


    /**
     * @var \TgReunion
     *
     * @ORM\ManyToOne(targetEntity="TgReunion", inversedBy="idSeance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reunion", referencedColumnName="id_reunion")
     * })
     */
    private $idReunion;

    /**
     * @var \TgReunion
     *
     * @ORM\ManyToOne(targetEntity="TgComite", inversedBy="idSeance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     */
    private $idComite;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="matin", type="boolean", nullable=true)
     */
    private $matin = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="apres_midi", type="boolean", nullable=true)
     */
    private $apresMidi = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_seance", type="date", nullable=false)
     */
    private $dtSeance;


    /**
     * @var TlReuPers
     * @ORM\OneToMany(targetEntity="TlReuPers", mappedBy="idSeance", cascade={"persist"})
     */
    private $tlReuPers;

    public function __construct()
    {
        $this->tlReuPers = new ArrayCollection();
    }

    public function getIdSeance(): ?int
    {
        return $this->idSeance;
    }

    public function getMatin(): ?bool
    {
        return $this->matin;
    }

    public function setMatin(?bool $matin): self
    {
        $this->matin = $matin;

        return $this;
    }

    public function getApresMidi(): ?bool
    {
        return $this->apresMidi;
    }

    public function setApresMidi(?bool $apresMidi): self
    {
        $this->apresMidi = $apresMidi;

        return $this;
    }

    public function getDtSeance(): ?\DateTimeInterface
    {
        return $this->dtSeance;
    }

    public function setDtSeance(\DateTimeInterface $dtSeance): self
    {
        $this->dtSeance = $dtSeance;

        return $this;
    }

    public function getIdReunion(): ?TgReunion
    {
        return $this->idReunion;
    }

    public function setIdReunion(?TgReunion $idReunion): self
    {
        $this->idReunion = $idReunion;

        return $this;
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
            $tlReuPer->setIdSeance($this);
        }

        return $this;
    }

    public function removeTlReuPer(TlReuPers $tlReuPer): self
    {
        if ($this->tlReuPers->contains($tlReuPer)) {
            $this->tlReuPers->removeElement($tlReuPer);
            // set the owning side to null (unless already changed)
            if ($tlReuPer->getIdSeance() === $this) {
                $tlReuPer->setIdSeance(null);
            }
        }

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

    public function __toString()
    {
        return $this->getIdReunion()->getLbTitre();
    }

}
