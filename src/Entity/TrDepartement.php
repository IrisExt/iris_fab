<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrDepartement
 *
 * @ORM\Table(name="tr_departement", uniqueConstraints={@ORM\UniqueConstraint(name="tr_departement_pk", columns={"id_departement"})})
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 */
class TrDepartement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_departement", type="bigint", nullable=false, options={"comment"="Identifiant du département "})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_departement_id_departement_seq", allocationSize=1, initialValue=1)
     */
    private $idDepartement;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_court", type="string", length=255, nullable=false, options={"comment"="Désignation du département"})
     */
    private $lbCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_long", type="string", length=50, nullable=false)
     */
    private $lbLong;


        /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgComite", mappedBy="idDepartement", cascade={"persist"})
     */
    private $idComite;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idComite = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdDepartement(): ?int
    {
        return $this->idDepartement;
    }

    public function getLbCourt(): ?string
    {
        return $this->lbCourt;
    }

    public function setLbCourt(string $lbCourt): self
    {
        $this->lbCourt = $lbCourt;

        return $this;
    }

    public function getLbLong(): ?string
    {
        return $this->lbLong;
    }

    public function setLbLong(string $lbLong): self
    {
        $this->lbLong = $lbLong;

        return $this;
    }

    /**
     * @return Collection|TgComite[]
     */
    public function getIdComite(): Collection
    {
        return $this->idComite;
    }


    public function __toString(): ?string
    {

        return $this->getLbLong();
    }

    public function addIdComite(TgComite $idComite): self
    {
        if (!$this->idComite->contains($idComite)) {
            $this->idComite[] = $idComite;
            $idComite->addIdDepartement($this);
        }

        return $this;
    }

    public function removeIdComite(TgComite $idComite): self
    {
        if ($this->idComite->contains($idComite)) {
            $this->idComite->removeElement($idComite);
            $idComite->removeIdDepartement($this);
        }

        return $this;
    }

}
