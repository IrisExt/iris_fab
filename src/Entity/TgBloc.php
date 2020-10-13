<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgBloc
 *
 * @ORM\Table(name="tg_bloc", uniqueConstraints={@ORM\UniqueConstraint(name="tg_bloc_pk", columns={"id_bloc"})})
 * @ORM\Entity
 */
class TgBloc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_bloc", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_bloc_id_bloc_seq", allocationSize=1, initialValue=1)
     */
    private $idBloc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_bloc", type="string", length=200, nullable=false)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbBloc;

    /**
     * @var string
     *
     * @ORM\Column(name="class_name", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $className;

    /**
     * @var TlBlocForm
     * @ORM\OneToMany(targetEntity="TlBlocForm", mappedBy="idBloc", cascade={"persist"})
     */
    private $tlBlocForm;



    public function __construct()
    {
        $this->tlBlocForm = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getLbBloc();
    }

    public function getIdBloc(): ?int
    {
        return $this->idBloc;
    }

    public function getLbBloc(): ?string
    {
        return $this->lbBloc;
    }

    public function setLbBloc(string $lbBloc): self
    {
        $this->lbBloc = $lbBloc;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(?string $className): self
    {
        $this->className = $className;

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
            $tlBlocForm->setIdBloc($this);
        }

        return $this;
    }

    public function removeTlBlocForm(TlBlocForm $tlBlocForm): self
    {
        if ($this->tlBlocForm->contains($tlBlocForm)) {
            $this->tlBlocForm->removeElement($tlBlocForm);
            // set the owning side to null (unless already changed)
            if ($tlBlocForm->getIdBloc() === $this) {
                $tlBlocForm->setIdBloc(null);
            }
        }

        return $this;
    }
}