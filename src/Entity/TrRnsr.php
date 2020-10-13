<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TrRnsr
 *
 * @ORM\Table(name="tr_rnsr", uniqueConstraints={@ORM\UniqueConstraint(name="tr_rnsr_pk", columns={"cd_rnsr"})})
 * @ORM\Entity
 */
class TrRnsr
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_rnsr", type="string", length=20, nullable=false)
     * @ORM\Id
     */
    private $cdRnsr;

    /**
     * @var \TgAdresse
     *
     * @ORM\ManyToOne(targetEntity="TgAdresse", inversedBy="cdRnsr", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_adresse", referencedColumnName="id_adresse")
     * })
     */
    private $idAdresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_laboratoire", type="string", length=150, nullable=true)
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "Le nom du Laboratoire est limité à {{ limit }} caractères")
     */
    private $lbLaboratoire;

    /**
     * @var TgOrganisme
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TgOrganisme" , mappedBy="cdRnsr")
     */
    private $idOrganisme;




    public function getCdRnsr(): string
    {
        return $this->cdRnsr;
    }

    public function setCdRnsr(string $cdRnsr): self
    {
        $this->cdRnsr = $cdRnsr;

        return $this;
    }

    public function getIdAdresse(): ?TgAdresse
    {
        return $this->idAdresse;
    }

    public function setIdAdresse(?TgAdresse $idAdresse): self
    {
        $this->idAdresse = $idAdresse;

        return $this;
    }

    public function getLbLaboratoire(): ?string
    {
        return $this->lbLaboratoire;
    }

    public function setLbLaboratoire(?string $lbLaboratoire): self
    {
        $this->lbLaboratoire = $lbLaboratoire;

        return $this;
    }

    /**
     * @return Collection|TgOrganisme[]
     */
    public function getIdOrganisme(): Collection
    {
        return $this->idOrganisme;
    }

    public function addIdOrganisme(TgOrganisme $idOrganisme): self
    {
        if (!$this->idOrganisme->contains($idOrganisme)) {
            $this->idOrganisme[] = $idOrganisme;
            $idOrganisme->setCdRnsr($this);
        }

        return $this;
    }

    public function removeIdOrganisme(TgOrganisme $idOrganisme): self
    {
        if ($this->idOrganisme->contains($idOrganisme)) {
            $this->idOrganisme->removeElement($idOrganisme);
            // set the owning side to null (unless already changed)
            if ($idOrganisme->getCdRnsr() === $this) {
                $idOrganisme->setCdRnsr(null);
            }
        }

        return $this;
    }


    public function __toString(): string
    {
       return $this->getCdRnsr();
    }


}
