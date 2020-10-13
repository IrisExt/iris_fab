<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_formulaire_appel", indexes={@ORM\Index(name="formulaire_appel_fk", columns={"id_formulaire"}), @ORM\Index(name="a_pour_appel_fk", columns={"id_appel"})})
 */
class TlFormulaireAppel
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgFormulaire", inversedBy="tlFormulaireAppel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formulaire", referencedColumnName="id_formulaire")
     * })
     */
    private $idFormulaire;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgAppelProj", inversedBy="tlFormulaireAppel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */

    private $idAppel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPhase", inversedBy="tlFormulaireAppel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase", referencedColumnName="id_phase")
     * })
     */

    private $idPhase;

    public function getIdFormulaire(): ?TgFormulaire
    {
        return $this->idFormulaire;
    }

    public function setIdFormulaire(?TgFormulaire $idFormulaire): self
    {
        $this->idFormulaire = $idFormulaire;

        return $this;
    }

    public function getIdAppel(): ?tgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?tgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function getIdPhase(): ?tgPhase
    {
        return $this->idPhase;
    }

    public function setIdPhase(?tgPhase $idPhase): self
    {
        $this->idPhase = $idPhase;

        return $this;
    }
}