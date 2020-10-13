<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMotCleCv
 *
 * @ORM\Table(name="tg_mot_cle_cv", indexes={@ORM\Index(name="mot_cle_par_cv_fk", columns={"id_personne"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgMotCleCvRepository")
 */
class TgMotCleCv
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_cv", type="bigint", nullable=false, options={"comment"="identifiant du mot cle"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mot_cle_cv_id_mc_cv_seq", allocationSize=1, initialValue=1)
     */
    private $idMcCv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_mc_fr", type="string", nullable=true, options={"comment"="libellé du mot clé"})
     */
    private $lbMcFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_mc_en", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbMcEn;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="idMcCv", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var int
     * @ORM\Column(name="ordre", type="integer", length=3, nullable=false)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $ordre;

    public function getIdMcCv(): ?int
    {
        return $this->idMcCv;
    }

    public function getLbMcFr(): ?string
    {
        return $this->lbMcFr;
    }

    public function setLbMcFr(?string $lbMcFr): self
    {
        $this->lbMcFr = $lbMcFr;

        return $this;
    }

    public function getLbMcEn(): ?string
    {
        return $this->lbMcEn;
    }

    public function setLbMcEn(?string $lbMcEn): self
    {
        $this->lbMcEn = $lbMcEn;

        return $this;
    }

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbMcFr();
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

}
