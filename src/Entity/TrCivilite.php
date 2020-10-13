<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCivilite
 *
 * @ORM\Table(name="tr_civilite", uniqueConstraints={@ORM\UniqueConstraint(name="tr_civilite_pk", columns={"id_civilite"})})
 * @ORM\Entity
 */
class TrCivilite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_civilite", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_civilite_id_civilite_seq", allocationSize=1, initialValue=1)
     */
    private $idCivilite;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite_longue", type="string", length=20, nullable=false, options={"comment"="Code civilité long: Monsieur, Madame , mademoiselle pour la gestion des courriers"})
     */
    private $civiliteLongue;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite_court", type="string", length=4, nullable=false, options={"comment"="Code civilité court: M;, Me, Mlle. "})
     */
    private $civiliteCourt;

    public function getIdCivilite(): ?int
    {
        return $this->idCivilite;
    }

    public function getCiviliteLongue(): ?string
    {
        return $this->civiliteLongue;
    }

    public function setCiviliteLongue(string $civiliteLongue): self
    {
        $this->civiliteLongue = $civiliteLongue;

        return $this;
    }

    public function getCiviliteCourt(): ?string
    {
        return $this->civiliteCourt;
    }

    public function setCiviliteCourt(string $civiliteCourt): self
    {
        $this->civiliteCourt = $civiliteCourt;

        return $this;
    }

    public function __toString()
    {
        return $this->getCiviliteLongue();
    }


}
