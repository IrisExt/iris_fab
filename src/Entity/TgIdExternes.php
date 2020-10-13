<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TrTypIdExt
 *
 * @ORM\Table(name="tg_id_externes")
 * @ORM\Entity(repositoryClass="App\Repository\IdExternesRepository")
 */
class TgIdExternes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ident_Ext", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_id_externes_id_Ident_ext_seq", allocationSize=1, initialValue=1)
     */
    private $idIdentExt;

    /**
     * @var string
     *
     * @ORM\Column(name="num_identifiant", type="string", length=50, nullable=false)
     *
     * @Assert\Type(type="integer")
     * @Assert\Length(
     *      min = 16,
     *      max = 16,
     *      minMessage = "L'ORCID est limité à {{ limit }} caractères",
     *      maxMessage = "L'ORCID est limité à {{ limit }} caractères",
     *      groups={"ParticipantType"}
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le numéro est limité à {{ limit }} caractères"
     * )
     *
     */
    private $numIdentifiant;

    /**
     * @var TrTypIdExt
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrTypIdExt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_ref_ext", referencedColumnName="id_type_ref_ext")
     * })
     */
    private $idTypeRefExt;

    /**
     * @var TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne" , inversedBy="idIdentExt", cascade={"remove"} )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    public function getIdIdentExt(): ?string
    {
        return $this->idIdentExt;
    }

      public function getIdTypeRefExt(): ?TrTypIdExt
    {
        return $this->idTypeRefExt;
    }

    public function setIdTypeRefExt(?TrTypIdExt $idTypeRefExt): self
    {
        $this->idTypeRefExt = $idTypeRefExt;

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

    public function getNumIdentifiant(): ?string
    {
        return $this->numIdentifiant;
    }

    public function setNumIdentifiant(string $numIdentifiant): self
    {
        $this->numIdentifiant = $numIdentifiant;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNumIdentifiant();
    }
}