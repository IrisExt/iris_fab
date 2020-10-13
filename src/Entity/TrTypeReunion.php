<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypeReunion
 *
 * @ORM\Table(name="tr_type_reunion", uniqueConstraints={@ORM\UniqueConstraint(name="tr_type_reunion_pk", columns={"id_type_reunion"})})
 * @ORM\Entity
 */
class TrTypeReunion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_reunion", type="bigint", nullable=false, options={"comment"="identifiant de la réunion"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_reunion_id_type_reunion_seq", allocationSize=1, initialValue=1)
     */
    private $idTypeReunion;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_type_reunion", type="string", length=50, nullable=false, options={"comment"="Désignation de la réunion"})
     */
    private $lbTypeReunion;

    public function getIdTypeReunion(): ?int
    {
        return $this->idTypeReunion;
    }

    public function getLbTypeReunion(): ?string
    {
        return $this->lbTypeReunion;
    }

    public function setLbTypeReunion(string $lbTypeReunion): self
    {
        $this->lbTypeReunion = $lbTypeReunion;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbTypeReunion();
    }


}
