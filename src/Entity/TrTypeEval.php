<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypeEval
 *
 * @ORM\Table(name="tr_type_eval", uniqueConstraints={@ORM\UniqueConstraint(name="tr_type_eval_pk", columns={"id_type"})})
 * @ORM\Entity
 */
class TrTypeEval
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_eval_id_type_seq", allocationSize=1, initialValue=1)
     */
    private $idType;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_description", type="string", length=50, nullable=false, options={"comment"="Explicitation de la r�le (affichable)"})
     */
    private $lbDescription;

    /**
     * @return string
     */
    public function getLbDescription(): string
    {
        return $this->lbDescription;
    }

    /**
     * @param string $lbDescription
     */
    public function setLbDescription(string $lbDescription): void
    {
        $this->lbDescription = $lbDescription;
    }

    /**
     * @return int
     */
    public function getIdType(): int
    {
        return $this->idType;
    }

    public function __toString()
    {
        return $this->getLbDescription();
    }


}
