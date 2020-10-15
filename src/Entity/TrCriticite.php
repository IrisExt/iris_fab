<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCriticite
 * 
 * @ORM\Table(name="tr_criticite")
 * @ORM\Entity(repositoryClass="App\Repository\TrCriticiteRepository")
 */
class TrCriticite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_criticite", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_criticite_id_criticite_seq", allocationSize=1, initialValue=1)
     */
    private $idCriticite;

    /**
     * @var int
     * 
     * @ORM\Column(name="code_criticite", type="bigint", nullable=false)
     */
    private $codeCriticite;

    /**
     * @var string
     * 
     * @ORM\Column(name="couleur_criticite", type="string", length=255, nullable=false)
     */
    private $couleurCriticite;

    public function getIdCriticite(): ?int
    {
        return $this->idCriticite;
    }

    public function getCodeCriticite(): ?int
    {
        return $this->codeCriticite;
    }

    public function setCodeCriticite(int $codeCriticite): self
    {
        $this->codeCriticite = $codeCriticite;

        return $this;
    }

    public function getCouleurCriticite(): ?string
    {
        return $this->couleurCriticite;
    }

    public function setCouleurCriticite(string $couleurCriticite): self
    {
        $this->couleurCriticite = $couleurCriticite;

        return $this;
    }
}
