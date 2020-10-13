<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgParametreApplication
 *
 * @ORM\Table(name="tg_parametre_application", uniqueConstraints={@ORM\UniqueConstraint(name="tg_parametre_application_pk", columns={"id_parametre"})})
 * @ORM\Entity
 */
class TgParametreApplication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_parametre", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_parametre_application_id_parametre_seq", allocationSize=1, initialValue=1)
     */
    private $idParametre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_code", type="string", length=20, nullable=false, options={"comment"="Code 4 lettres utilisé dans le n° de la convention n° de la convention (financement)"})
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


}
