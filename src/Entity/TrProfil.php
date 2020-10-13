<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrProfil
 *
 * @ORM\Table(name="tr_profil", uniqueConstraints={@ORM\UniqueConstraint(name="tr_profil_pk", columns={"id_profil"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProfilRepository")
 */
class TrProfil
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_profil", type="bigint", nullable=false, options={"comment"="idnetifiant du profil de l'utilisateur"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_profil_id_profil_seq", allocationSize=1, initialValue=1)
     */
    private $idProfil;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_profil", type="string", length=50, nullable=false, options={"comment"="désignation du profil de l'utilisateur"})
     */
    private $lbProfil;


    /**
     * @var string
     *
     * @ORM\Column(name="cd_profil", type="string", length=5, nullable=false, options={"comment"="désignation du profil de l'utilisateur accronyme"})
     */
    private $cdProfil;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_role", type="string", length=50, nullable=true, options={"comment"="désignation du role de l'utilisateur pour symfony"})
     */
    private $lbRole;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TrClasseFormulaire", mappedBy="idProfil")
     */
    private $idClasseFormulaire;

    public function __construct()
    {
        $this->idClasseFormulaire = new ArrayCollection();
    }


    public function getIdProfil(): ?int
    {
        return $this->idProfil;
    }

    public function getLbProfil(): ?string
    {
        return $this->lbProfil;
    }

    public function setLbProfil(string $lbProfil): self
    {
        $this->lbProfil = $lbProfil;

        return $this;
    }



    public function getCdProfil(): ?string
    {
        return $this->cdProfil;
    }

    public function setCdProfil(string $cdProfil): self
    {
        $this->cdProfil = $cdProfil;

        return $this;
    }



    public function getLbRole(): ?string
    {
        return $this->lbRole;
    }

    public function setLbRole(?string $lbRole): self
    {
        $this->lbRole = $lbRole;

        return $this;
    }

    public function __toString() :string
    {
        return $this->getLbProfil();
    }

    /**
     * @return Collection|TrClasseFormulaire[]
     */
    public function getIdClasseFormulaire(): Collection
    {
        return $this->idClasseFormulaire;
    }

    public function addIdClasseFormulaire(TrClasseFormulaire $idClasseFormulaire): self
    {
        if (!$this->idClasseFormulaire->contains($idClasseFormulaire)) {
            $this->idClasseFormulaire[] = $idClasseFormulaire;
            $idClasseFormulaire->addIdProfil($this);
        }

        return $this;
    }

    public function removeIdClasseFormulaire(TrClasseFormulaire $idClasseFormulaire): self
    {
        if ($this->idClasseFormulaire->contains($idClasseFormulaire)) {
            $this->idClasseFormulaire->removeElement($idClasseFormulaire);
            $idClasseFormulaire->removeIdProfil($this);
        }

        return $this;
    }

}
