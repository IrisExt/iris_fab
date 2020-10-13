<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_avis_possibles")
 */

class TlAvisPossibles
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TrAvisProjet", inversedBy="avisPossibles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_avis", referencedColumnName="cd_avis")
     * })
     */

    private $cdAvis;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgComite", inversedBy="avisPossibles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     */
    private $idComite;

    /**
     * @var float
     * @ORM\Column(name="pourcent_max", type="float", length=8, nullable=true)
     */
    private $pourcentMax;

    /**
     * @var float
     * @ORM\Column(name="pourcent_min", type="float", length=8, nullable=true)
     */
    private $pourcentMin;


    public function getPourcentMax(): ?float
    {
        return $this->pourcentMax;
    }

    public function setPourcentMax(?float $pourcentMax): self
    {
        $this->pourcentMax = $pourcentMax;

        return $this;
    }

    public function getPourcentMin(): ?float
    {
        return $this->pourcentMin;
    }

    public function setPourcentMin(?float $pourcentMin): self
    {
        $this->pourcentMin = $pourcentMin;

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

    public function getCdAvis(): ?TrAvisProjet
    {
        return $this->cdAvis;
    }

    public function setCdAvis(?TrAvisProjet $cdAvis): self
    {
        $this->cdAvis = $cdAvis;

        return $this;
    }

}