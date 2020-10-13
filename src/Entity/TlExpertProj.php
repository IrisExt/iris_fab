<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_expert_proj")
 */

class TlExpertProj
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgProjet", inversedBy="experProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="experProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */

    private $idPersonne;

    /**
     * @var string
     * @ORM\Column(name="cd_soll_expert", type="string", length=3, nullable=true)
     */
    private $cdSollExpert;

    /**
     * @return mixed
     */
    public function getIdProjet()
    {
        return $this->idProjet;
    }

    /**
     * @param mixed $idProjet
     */
    public function setIdProjet($idProjet): void
    {
        $this->idProjet = $idProjet;
    }

    /**
     * @return mixed
     */
    public function getIdPersonne()
    {
        return $this->idPersonne;
    }

    /**
     * @param mixed $idPersonne
     */
    public function setIdPersonne($idPersonne): void
    {
        $this->idPersonne = $idPersonne;
    }

    /**
     * @return string
     */
    public function getCdSollExpert(): string
    {
        return $this->cdSollExpert;
    }

    /**
     * @param string $cdSollExpert
     */
    public function setCdSollExpert(string $cdSollExpert): void
    {
        $this->cdSollExpert = $cdSollExpert;
    }


}