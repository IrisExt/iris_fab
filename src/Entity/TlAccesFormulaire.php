<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TlAccesFormulaire
 *
 * @ORM\Table(name="tl_acces_formulaire", uniqueConstraints={@ORM\UniqueConstraint(name="tl_acces_formulaire_pk", columns={"id_profil", "id_formulaire", "id_phase_ref"})}, indexes={@ORM\Index(name="tl_acces_formulaire3_fk", columns={"id_phase_ref"}), @ORM\Index(name="tl_acces_formulaire_fk", columns={"id_profil"}), @ORM\Index(name="tl_acces_formulaire2_fk", columns={"id_formulaire"})})
 * @ORM\Entity
 */
class TlAccesFormulaire
{
    /**
     * @var string
     *
     * @ORM\Column(name="type_acces", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $typeAcces;

    /**
     * @var \TrProfil
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TrProfil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil")
     * })
     */
    private $idProfil;

    /**
     * @var \TgFormulaire
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgFormulaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formulaire", referencedColumnName="id_formulaire")
     * })
     */
    private $idFormulaire;

    /**
     * @var \TrPhase
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TrPhase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase_ref", referencedColumnName="id_phase_ref")
     * })
     */
    private $idPhaseRef;

    public function getTypeAcces(): ?string
    {
        return $this->typeAcces;
    }

    public function setTypeAcces(string $typeAcces): self
    {
        $this->typeAcces = $typeAcces;

        return $this;
    }

    public function getIdProfil(): ?TrProfil
    {
        return $this->idProfil;
    }

    public function setIdProfil(?TrProfil $idProfil): self
    {
        $this->idProfil = $idProfil;

        return $this;
    }

    public function getIdFormulaire(): ?TgFormulaire
    {
        return $this->idFormulaire;
    }

    public function setIdFormulaire(?TgFormulaire $idFormulaire): self
    {
        $this->idFormulaire = $idFormulaire;

        return $this;
    }

    public function getIdPhaseRef(): ?TrPhase
    {
        return $this->idPhaseRef;
    }

    public function setIdPhaseRef(?TrPhase $idPhaseRef): self
    {
        $this->idPhaseRef = $idPhaseRef;

        return $this;
    }


}
