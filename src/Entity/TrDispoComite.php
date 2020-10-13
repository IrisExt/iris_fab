<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrDispoComite
 *
 * @ORM\Table(name="tr_dispo_comite", uniqueConstraints={@ORM\UniqueConstraint(name="tr_dispo_comite_pk", columns={"id_choix"})})
 * @ORM\Entity
 */
class TrDispoComite
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_choix", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_dispo_comite_id_choix_seq", allocationSize=1, initialValue=1)
     */
    private $idChoix;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_ordre", type="smallint", nullable=false, options={"comment"="ordre d'affichage"})
     */
    private $nbOrdre;



    public function getIdChoix(): ?string
    {
        return $this->idChoix;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    public function getNbOrdre(): ?int
    {
        return $this->nbOrdre;
    }

    public function setNbOrdre(int $nbOrdre): self
    {
        $this->nbOrdre = $nbOrdre;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbNom();
    }

}
