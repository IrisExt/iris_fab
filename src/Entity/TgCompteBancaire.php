<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgCompteBancaire
 *
 * @ORM\Table(name="tg_compte_bancaire", uniqueConstraints={@ORM\UniqueConstraint(name="compte_bancaire_pk", columns={"id_compte"})})
 * @ORM\Entity
 */
class TgCompteBancaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_compte", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_compte_bancaire_id_compte_seq", allocationSize=1, initialValue=1)
     */
    private $idCompte;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iban", type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="alpha")
     * @Assert\Length(
     *      min = 18,
     *      max = 30,
     *      minMessage = "Le titire est limité à {{ limit }} caractères",
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *      groups={"PartenaireType"}
     * )
     */
    private $iban;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="integer")
     * @Assert\Length(
     *      min = 23,
     *      max = 23,
     *      minMessage = "Le titire est limité à {{ limit }} caractères",
     *      maxMessage = "Le titire est limité à {{ limit }} caractères",
     *      groups={"PartenaireType"}
     * )
     */
    private $rib;

    /**
     * @var string|null
     *
     * @ORM\Column(name="banque", type="string", length=40, nullable=true)
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $banque;

    public function getIdCompte(): ?int
    {
        return $this->idCompte;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(?string $banque): self
    {
        $this->banque = $banque;

        return $this;
    }


}
