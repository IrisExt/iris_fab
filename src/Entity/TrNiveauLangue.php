<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrNiveauLangue
 *
 * @ORM\Table(name="tr_niveau_langue", uniqueConstraints={@ORM\UniqueConstraint(name="tr_niveau_langue_pk", columns={"id_niveau"})})
 * @ORM\Entity
 */
class TrNiveauLangue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_niveau", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_niveau_langue_id_niveau_seq", allocationSize=1, initialValue=1)
     */
    private $idNiveau;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_niveau", type="string", length=40, nullable=false)
     */
    private $lbNiveau;

    public function getIdNiveau(): ?int
    {
        return $this->idNiveau;
    }

    public function getLbNiveau(): ?string
    {
        return $this->lbNiveau;
    }

    public function setLbNiveau(string $lbNiveau): self
    {
        $this->lbNiveau = $lbNiveau;

        return $this;
    }

    public function __toString():string
    {
        return $this->getLbNiveau();

    }


}
