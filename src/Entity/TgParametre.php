<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgParametre
 *
 * @ORM\Table(name="tg_parametre", uniqueConstraints={@ORM\UniqueConstraint(name="tg_parametre_pk", columns={"id_parametre"})}, indexes={@ORM\Index(name="sont_relatif_a_fk", columns={"id_appel"})})
 * @ORM\Entity
 */
class TgParametre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_parametre", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_parametre_id_parametre_seq", allocationSize=1, initialValue=1)
     */
    private $idParametre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_code", type="string", length=20, nullable=false)
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_valeur", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbValeur;

    /**
     * @var \TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    public function getIdParametre(): ?int
    {
        return $this->idParametre;
    }

    public function getLbCode(): ?string
    {
        return $this->lbCode;
    }

    public function setLbCode(string $lbCode): self
    {
        $this->lbCode = $lbCode;

        return $this;
    }

    public function getLbValeur(): ?string
    {
        return $this->lbValeur;
    }

    public function setLbValeur(?string $lbValeur): self
    {
        $this->lbValeur = $lbValeur;

        return $this;
    }

    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbValeur();
    }


}
