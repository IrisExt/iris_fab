<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrStsEvaluation
 *
 * @ORM\Table(name="tr_st_affect", uniqueConstraints={@ORM\UniqueConstraint(name="tr_st_affect_pk", columns={"id_st_affect"})})
 * @ORM\Entity(repositoryClass="App\Repository\TrStAffectRepository")
 */
class TrStAffect
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_st_affect", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_st_affect_id_st_affect_seq", allocationSize=1, initialValue=1)
     */
    private $idStAffect;

    /**
     * @var string
     *
     * @ORM\Column(name="symbole", type="string", length=3, nullable=false)
     */
    private $symbole;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", length=2, nullable=false)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_couleur", type="string", length=20, nullable=true)
     */
    private $lbCouleur;

    public function getIdStAffect(): ?string
    {
        return $this->idStAffect;
    }

    public function getSymbole(): ?string
    {
        return $this->symbole;
    }

    public function setSymbole(string $symbole): self
    {
        $this->symbole = $symbole;

        return $this;
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getLbCouleur(): ?string
    {
        return $this->lbCouleur;
    }

    public function setLbCouleur(?string $lbCouleur): self
    {
        $this->lbCouleur = $lbCouleur;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbNom();
    }

}