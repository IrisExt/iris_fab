<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgNiveauPhase
 *
 * @ORM\Table(name="tg_niveau_phase", indexes={@ORM\Index(name="IDX_B31699475F3897FB", columns={"id_phase"}), @ORM\Index(name="IDX_B3169947EFB5928D", columns={"id_appel"})})
 * @ORM\Entity(repositoryClass="App\Repository\NiveauPhaseRepository")
 */
class TgNiveauPhase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_niveau_phase", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_niveau_phase_id_niveau_phase_seq", allocationSize=1, initialValue=1)
     */
    private $idNiveauPhase;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_debut", type="date", nullable=true)
     */
    private $dhDebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_fin", type="date", nullable=true)
     */
    private $dhFin;

    /**
     * @var int
     *
     * @ORM\Column(name="ord_phase", type="integer", nullable=false)
     */
    private $ordPhase;

    /**
     * @var \TrNiveau
     *
     * @ORM\ManyToOne(targetEntity="TrNiveau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_niveu", referencedColumnName="id_type_niveu")
     * })
     */
    private $idTypeNiveu;

    /**
     * @var TgPhase
     *
     * @ORM\ManyToOne(targetEntity="TgPhase" ,inversedBy="idNiveauPhase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase", referencedColumnName="id_phase")
     * })
     */
    private $idPhase;

    /**
     * @var \TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj" ,cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    public function getIdNiveauPhase(): ?int
    {
        return $this->idNiveauPhase;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(?string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }


    public function getDhDebut(): ?\DateTimeInterface
    {
        return $this->dhDebut;
    }

    public function setDhDebut(?\DateTimeInterface $dhDebut): self
    {
        $this->dhDebut = $dhDebut;

        return $this;
    }

    public function getDhFin(): ?\DateTimeInterface
    {
        return $this->dhFin;
    }

    public function setDhFin(?\DateTimeInterface $dhFin): self
    {
        $this->dhFin = $dhFin;

        return $this;
    }

    public function getOrdPhase(): ?int
    {
        return $this->ordPhase;
    }

    public function setOrdPhase(int $ordPhase): self
    {
        $this->ordPhase = $ordPhase;

        return $this;
    }

    public function getIdTypeNiveu(): ?TrNiveau
    {
        return $this->idTypeNiveu;
    }

    public function setIdTypeNiveu(?TrNiveau $idTypeNiveu): self
    {
        $this->idTypeNiveu = $idTypeNiveu;

        return $this;
    }

    public function getIdPhase(): ?TgPhase
    {
        return $this->idPhase;
    }

    public function setIdPhase(?TgPhase $idPhase): self
    {
        $this->idPhase = $idPhase;

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

    public function __toString():string
    {
        return $this->getIdTypeNiveu()->getLbNom();
    }


}
