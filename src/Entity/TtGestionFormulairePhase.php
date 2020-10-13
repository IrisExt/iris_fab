<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgAppelProj
 *
 * @ORM\Table(name="tt_gestion_formulaire_phase")
 * @ORM\Entity(repositoryClass="App\Repository\TtGestionFormulairePhaseRepository")
 */
class TtGestionFormulairePhase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tt_gestion_formulaire_phase_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_appel", type="bigint", nullable=true)
     *
     */
    private $idAppel;

    /**
     * @var int
     *
     * @ORM\Column(name="id_phase", type="bigint", nullable=false)
     *
     */
    private $idPhase;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_formulaire", type="bigint", nullable=true)
     *
     */
    private $idFormulaire;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="string", length=255, nullable=true)
     *
     */
    private $tableName;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=false)
     *
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     *
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean", nullable=false)
     *
     */
    private $visibility;

    /**
     * @var string
     *
     * @ORM\Column(name="permissions", type="string", length=1, nullable=false)
     *
     */
    private $permissions;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saved", type="boolean", nullable=false)
     *
     */
    private $saved;

    /**
     * @var string
     *
     * @ORM\Column(name="info_field", type="text", nullable=true)
     *
     */
    private $infoField;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAppel(): ?int
    {
        return $this->idAppel;
    }

    public function setIdAppel(?int $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function getIdPhase(): ?int
    {
        return $this->idPhase;
    }

    public function setIdPhase(int $idPhase): self
    {
        $this->idPhase = $idPhase;

        return $this;
    }

    public function getIdFormulaire(): ?int
    {
        return $this->idFormulaire;
    }

    public function setIdFormulaire(?int $idFormulaire): self
    {
        $this->idFormulaire = $idFormulaire;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(?string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getPermissions(): ?string
    {
        return $this->permissions;
    }

    public function setPermissions(string $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getSaved(): ?bool
    {
        return $this->saved;
    }

    public function setSaved(bool $saved): self
    {
        $this->saved = $saved;

        return $this;
    }

    public function getInfoField(): ?string
    {
        return $this->infoField;
    }

    public function setInfoField(?string $infoField): self
    {
        $this->infoField = $infoField;

        return $this;
    }




}
