<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgDispoExp
 *
 * @ORM\Table(name="tg_dispo_exp", uniqueConstraints={@ORM\UniqueConstraint(name="tg_disponilibte_pk", columns={"id_appel", "id_personne"})}, indexes={@ORM\Index(name="pers_dispo_fk", columns={"id_personne"}), @ORM\Index(name="appel_dispo_fk", columns={"id_appel"}), @ORM\Index(name="choix_dispo_fk", columns={"id_choix_expert"})})
 * @ORM\Entity
 */
class TgDispoExp
{
    /**
     * @var \TgAppelProj
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    /**
     * @var \TrChoixDispoExpert
     *
     * @ORM\ManyToOne(targetEntity="TrChoixDispoExpert")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_choix_expert", referencedColumnName="id_choix_expert")
     * })
     */
    private $idChoixExpert;

    /**
     * @var \TgPersonne
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function getIdChoixExpert(): ?TrChoixDispoExpert
    {
        return $this->idChoixExpert;
    }

    public function setIdChoixExpert(?TrChoixDispoExpert $idChoixExpert): self
    {
        $this->idChoixExpert = $idChoixExpert;

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


}
