<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_personne_Mc_Libre")
 **/
class TlPersonneMcLibre
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="tlPersonneMcLibre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgMcLibre", inversedBy="tlPersonneMcLibre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mc_libre", referencedColumnName="id_mc_libre")
     * })
     */
    private $idMcLibre;

    /**
     * @var int
     * @ORM\Column(name="ordre", type="integer", length=3, nullable=false)
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $ordre;



    public function __toString(): string
    {
//        return $this->getIdMcLibre()->getLbNomFr();
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

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    public function getIdMcLibre(): ?TgMcLibre
    {
        return $this->idMcLibre;
    }

    public function setIdMcLibre(?TgMcLibre $idMcLibre): self
    {
        $this->idMcLibre = $idMcLibre;

        return $this;
    }

}