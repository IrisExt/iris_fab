<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TlAffectUtilisateurRepository")
 * @ORM\Table(name="tl_affect_utilisateur")
 */
class TlAffectUtilisateur
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgPersonne", inversedBy="tlAffectUtilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $IdPersonne;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgAffectation", inversedBy="tlAffectUtilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_affectation", referencedColumnName="id_affectation")
     * })
     */
    private $IdAffectation;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->IdPersonne;
    }

    public function setIdPersonne(?TgPersonne $IdPersonne): self
    {
        $this->IdPersonne = $IdPersonne;

        return $this;
    }

    public function getIdAffectation(): ?TgAffectation
    {
        return $this->IdAffectation;
    }

    public function setIdAffectation(?TgAffectation $IdAffectation): self
    {
        $this->IdAffectation = $IdAffectation;

        return $this;
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
