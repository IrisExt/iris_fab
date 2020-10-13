<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_reu_pers", indexes={@ORM\Index(name="participation_fk", columns={"id_personne"}), @ORM\Index(name="a_pour_seance_fk", columns={"id_seance"})})
 */
class TlReuPers
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="tlReuPers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgSeance", inversedBy="tlReuPers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_seance", referencedColumnName="id_seance")
     * })
     */

    private $idSeance;

    /**
     * @var int
     * @ORM\Column(name="bl_present", type="integer", length=2, nullable=false)
     */
    private $blPresent;

    public function getBlPresent(): ?int
    {
        return $this->blPresent;
    }

    public function setBlPresent(int $blPresent): self
    {
        $this->blPresent = $blPresent;

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

    public function getIdSeance(): ?TgSeance
    {
        return $this->idSeance;
    }

    public function setIdSeance(?TgSeance $idSeance): self
    {
        $this->idSeance = $idSeance;

        return $this;
    }
}