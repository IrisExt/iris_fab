<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_bloc_form", indexes={@ORM\Index(name="formulaire_fk", columns={"id_formulaire"}), @ORM\Index(name="a_pour_bloc_fk", columns={"id_bloc"})})
 */

class TlBlocForm
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgFormulaire", inversedBy="tlBlocForm")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formulaire", referencedColumnName="id_formulaire")
     * })
     */
    private $idFormulaire;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgBloc", inversedBy="tlBlocForm")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_bloc", referencedColumnName="id_bloc")
     * })
     */

    private $idBloc;

    /**
     * @var int
     * @ORM\Column(name="rang", type="integer", length=2, nullable=false)
     * @Assert\Length(
     *      max = 2,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $ordre;

    /**
     * @var int
     * @ORM\Column(name="bloc_parent", type="integer", length=2, nullable=true)
     */
    private $blocParent;

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getBlocParent(): ?int
    {
        return $this->blocParent;
    }

    public function setBlocParent(?int $blocParent): self
    {
        $this->blocParent = $blocParent;

        return $this;
    }

    public function getIdFormulaire(): ?TgFormulaire
    {
        return $this->idFormulaire;
    }

    public function setIdFormulaire(?TgFormulaire $idFormulaire): self
    {
        $this->idFormulaire = $idFormulaire;

        return $this;
    }

    public function getIdBloc(): ?TgBloc
    {
        return $this->idBloc;
    }

    public function setIdBloc(?TgBloc $idBloc): self
    {
        $this->idBloc = $idBloc;

        return $this;
    }

}