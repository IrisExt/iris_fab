<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMotCleCps
 *
 * @ORM\Table(name="tg_mot_cle_cps", uniqueConstraints={@ORM\UniqueConstraint(name="tg_mot_cle_cps_pk", columns={"id_mc_cps"})}, indexes={@ORM\Index(name="association_80_fk", columns={"id_personne"})})
 * @ORM\Entity
 */
class TgMotCleCps
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mc_cps", type="bigint", nullable=false, options={"comment"="identifiant du mot cle"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_mot_cle_cps_id_mc_cps_seq", allocationSize=1, initialValue=1)
     */
    private $idMcCps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_mc_cps_fr", type="string", length=200, nullable=true)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbMcCpsFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_mc_cps_en", type="string", length=200, nullable=true)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbMcCpsEn;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne" , inversedBy="idMcCps", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    public function getIdMcCps(): ?int
    {
        return $this->idMcCps;
    }

    public function getLbMcCpsFr(): ?string
    {
        return $this->lbMcCpsFr;
    }

    public function setLbMcCpsFr(?string $lbMcCpsFr): self
    {
        $this->lbMcCpsFr = $lbMcCpsFr;

        return $this;
    }

    public function getLbMcCpsEn(): ?string
    {
        return $this->lbMcCpsEn;
    }

    public function setLbMcCpsEn(?string $lbMcCpsEn): self
    {
        $this->lbMcCpsEn = $lbMcCpsEn;

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

    public function __toString() :string
    {
        return $this->getLbMcCpsFr();
    }


}
