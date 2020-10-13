<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TgPoste
 *
 * @ORM\Table(name="tg_poste", uniqueConstraints={@ORM\UniqueConstraint(name="tg_poste_pk", columns={"id_cv", "id_organisme"})}, indexes={@ORM\Index(name="poste_concerne_cv_fk", columns={"id_cv"}), @ORM\Index(name="a_pour_fonction_fk", columns={"id_fonction"}), @ORM\Index(name="poste_dans_l_organisme_fk", columns={"id_organisme"})})
 * @ORM\Entity
 */
class TgPoste
{
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_debut", type="date", nullable=true)
     *
     */
    private $dtDebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_fin", type="date", nullable=true)
     */
    private $dtFin;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_anterieur", type="boolean", nullable=true)
     */
    private $blAnterieur;

    /**
     * @var \TrFonction
     *
     * @ORM\ManyToOne(targetEntity="TrFonction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_fonction", referencedColumnName="id_fonction")
     * })
     */
    private $idFonction;

    /**
     * @var \TgCv
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgCv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cv", referencedColumnName="id_cv")
     * })
     */
    private $idCv;

    /**
     * @var \TgOrganisme
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgOrganisme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisme", referencedColumnName="id_organisme")
     * })
     */
    private $idOrganisme;

    public function getDtDebut(): ?\DateTimeInterface
    {
        return $this->dtDebut;
    }

    public function setDtDebut(?\DateTimeInterface $dtDebut): self
    {
        $this->dtDebut = $dtDebut;

        return $this;
    }

    public function getDtFin(): ?\DateTimeInterface
    {
        return $this->dtFin;
    }

    public function setDtFin(?\DateTimeInterface $dtFin): self
    {
        $this->dtFin = $dtFin;

        return $this;
    }

    public function getBlAnterieur(): ?bool
    {
        return $this->blAnterieur;
    }

    public function setBlAnterieur(?bool $blAnterieur): self
    {
        $this->blAnterieur = $blAnterieur;

        return $this;
    }

    public function getIdFonction(): ?TrFonction
    {
        return $this->idFonction;
    }

    public function setIdFonction(?TrFonction $idFonction): self
    {
        $this->idFonction = $idFonction;

        return $this;
    }

    public function getIdCv(): ?TgCv
    {
        return $this->idCv;
    }

    public function setIdCv(?TgCv $idCv): self
    {
        $this->idCv = $idCv;

        return $this;
    }

    public function getIdOrganisme(): ?TgOrganisme
    {
        return $this->idOrganisme;
    }

    public function setIdOrganisme(?TgOrganisme $idOrganisme): self
    {
        $this->idOrganisme = $idOrganisme;

        return $this;
    }


}
