<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_personne_Mc_Erc")
 **/
class TlPersonneMcErc
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="tlPersonneMcErc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgMotCleErc", inversedBy="tlPersonneMcErc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mc_erc", referencedColumnName="id_mc_erc")
     * })
     */
    private $idMcErc;

    /**
     * @var int
     * @ORM\Column(name="ordre", type="integer", length=3, nullable=false)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $ordre;

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    public function getIdMcErc(): ?TgMotCleErc
    {
        return $this->idMcErc;
    }

    public function setIdMcErc(?TgMotCleErc $idMcErc): self
    {
        $this->idMcErc = $idMcErc;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getIdMcErc()->getLbNomFr();
    }

}