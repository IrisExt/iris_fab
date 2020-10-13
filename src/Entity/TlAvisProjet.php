<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_avis_projet")
 */

class TlAvisProjet
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgProjet", inversedBy="avisProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="avisProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */

    private $idPersonne;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TrAvisProjet", inversedBy="avisProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_avis", referencedColumnName="cd_avis")
     * })
     */

    private $cdAvis;

    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

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

    public function getCdAvis(): ?TrAvisProjet
    {
        return $this->cdAvis;
    }

    public function setCdAvis(?TrAvisProjet $cdAvis): self
    {
        $this->cdAvis = $cdAvis;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getCdAvis()->getLbNomFr();
    }


}