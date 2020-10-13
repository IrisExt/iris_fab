<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TrPays
 *
 * @ORM\Table(name="tr_siret", uniqueConstraints={@ORM\UniqueConstraint(name="tr_siret_pk", columns={"siret"})})
 * @ORM\Entity
 */
class TrSiret
{
    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=14, nullable=false)
     * @ORM\Id
     */
    private $siret;

    /**
     * @var \TgAdresse
     *
     * @ORM\ManyToOne(targetEntity="TgAdresse", inversedBy="siret", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_adresse", referencedColumnName="id_adresse")
     * })
     */
    private $idAdresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sigle", type="string", length=40, nullable=true)
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $sigle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_unite", type="string", length=10, nullable=true)
     */
    private $codeUnite;



    /**
     * @var TgOrganisme
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TgOrganisme" , mappedBy="siret")
     */
    private $idOrganisme;

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

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

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

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
            $idOrganisme->setSiret($this);
        }

        return $this;
    }

    public function removeIdOrganisme(TgOrganisme $idOrganisme): self
    {
        if ($this->idOrganisme->contains($idOrganisme)) {
            $this->idOrganisme->removeElement($idOrganisme);
            // set the owning side to null (unless already changed)
            if ($idOrganisme->getSiret() === $this) {
                $idOrganisme->setSiret(null);
            }
        }

        return $this;
    }

    public function getCodeUnite(): ?string
    {
        return $this->codeUnite;
    }

    public function setCodeUnite(?string $codeUnite): self
    {
        $this->codeUnite = $codeUnite;

        return $this;
    }


    public function __toString() : string
    {
       return $this->getSiret();
    }


}
