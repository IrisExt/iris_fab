<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgFormulaire
 *
 * @ORM\Table(name="tg_formulaire", uniqueConstraints={@ORM\UniqueConstraint(name="formulaire_pk", columns={"id_formulaire"})})
 * @ORM\Entity
 */
class TgFormulaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_formulaire", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_formulaire_id_formulaire_seq", allocationSize=1, initialValue=1)
     */
    private $idFormulaire;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_formulaire", type="string", length=200, nullable=false)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbFormulaire;

    /**
     * @var TrClasseFormulaire
     *
     * @ORM\ManyToOne(targetEntity="TrClasseFormulaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_classe_formulaire", referencedColumnName="id_classe_formulaire")
     * })
     */
    private $idClasseFormulaire;

    /**
     * @var TlBlocForm
     * @ORM\OneToMany(targetEntity="App\Entity\TlBlocForm", mappedBy="idFormulaire", cascade={"persist"})
     */
    private $tlBlocForm;

    /**
     * @var \TlFormulaireAppel
     * @ORM\OneToMany(targetEntity="App\Entity\TlFormulaireAppel", mappedBy="idFormulaire", cascade={"persist"})
     */
    private $tlFormulaireAppel;


    public function __construct()
    {
        $this->tlBlocForm = new ArrayCollection();
        $this->idAppel = new ArrayCollection();
        $this->tlFormulaireAppel = new ArrayCollection();
    }


    public function getIdFormulaire(): ?int
    {
        return $this->idFormulaire;
    }

    public function getLbFormulaire(): ?string
    {
        return $this->lbFormulaire;
    }

    public function setLbFormulaire(string $lbFormulaire): self
    {
        $this->lbFormulaire = $lbFormulaire;

        return $this;
    }

    /**
     * @return Collection|TlBlocForm[]
     */
    public function getTlBlocForm(): Collection
    {
        return $this->tlBlocForm;
    }

    public function addTlBlocForm(TlBlocForm $tlBlocForm): self
    {
        if (!$this->tlBlocForm->contains($tlBlocForm)) {
            $this->tlBlocForm[] = $tlBlocForm;
            $tlBlocForm->setIdFormulaire($this);
        }

        return $this;
    }

    public function removeTlBlocForm(TlBlocForm $tlBlocForm): self
    {
        if ($this->tlBlocForm->contains($tlBlocForm)) {
            $this->tlBlocForm->removeElement($tlBlocForm);
            // set the owning side to null (unless already changed)
            if ($tlBlocForm->getIdFormulaire() === $this) {
                $tlBlocForm->setIdFormulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TgAppelProj[]
     */
    public function getIdAppel(): Collection
    {
        return $this->idAppel;
    }

    public function addIdAppel(TgAppelProj $idAppel): self
    {
        if (!$this->idAppel->contains($idAppel)) {
            $this->idAppel[] = $idAppel;
            $idAppel->addIdFormulaire($this);
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
            $idAppel->removeIdFormulaire($this);
        }

        return $this;
    }

    public function getIdClasseFormulaire(): ?TrClasseFormulaire
    {
        return $this->idClasseFormulaire;
    }

    public function setIdClasseFormulaire(?TrClasseFormulaire $idClasseFormulaire): self
    {
        $this->idClasseFormulaire = $idClasseFormulaire;

        return $this;
    }

    /**
     * @return Collection|TlFormulaireAppel[]
     */
    public function getTlFormulaireAppel(): Collection
    {
        return $this->tlFormulaireAppel;
    }

    public function addTlFormulaireAppel(TlFormulaireAppel $tlFormulaireAppel): self
    {
        if (!$this->tlFormulaireAppel->contains($tlFormulaireAppel)) {
            $this->tlFormulaireAppel[] = $tlFormulaireAppel;
            $tlFormulaireAppel->setIdFormulaire($this);
        }

        return $this;
    }

    public function removeTlFormulaireAppel(TlFormulaireAppel $tlFormulaireAppel): self
    {
        if ($this->tlFormulaireAppel->contains($tlFormulaireAppel)) {
            $this->tlFormulaireAppel->removeElement($tlFormulaireAppel);
            // set the owning side to null (unless already changed)
            if ($tlFormulaireAppel->getIdFormulaire() === $this) {
                $tlFormulaireAppel->setIdFormulaire(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getLbFormulaire();
    }

}
