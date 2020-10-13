<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgPublication
 *
 * @ORM\Table(name="tg_publication", uniqueConstraints={@ORM\UniqueConstraint(name="tg_publication_pk", columns={"id_publication"})})
 * @ORM\Entity
 */
class TgPublication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_publication", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_publication_id_publication_seq", allocationSize=1, initialValue=1)
     */
    private $idPublication;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_titre", type="string", length=50, nullable=false, options={"comment"="Titre du comit�"})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_justification", type="text", nullable=false)
     */
    private $lbJustification;

    /**
     * @var TlCvPubl
     * @ORM\OneToMany(targetEntity="App\Entity\TlCvPubl", mappedBy="idPublication", cascade={"persist"})
     */
    private $tlCvPubl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tlCvPubl = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdPublication(): ?string
    {
        return $this->idPublication;
    }

    public function getLbTitre(): ?string
    {
        return $this->lbTitre;
    }

    public function setLbTitre(string $lbTitre): self
    {
        $this->lbTitre = $lbTitre;

        return $this;
    }

    public function getLbJustification(): ?string
    {
        return $this->lbJustification;
    }

    public function setLbJustification(string $lbJustification): self
    {
        $this->lbJustification = $lbJustification;

        return $this;
    }

    /**
     * @return Collection|TlCvPubl[]
     */
    public function getTlCvPubl(): Collection
    {
        return $this->tlCvPubl;
    }

    public function addTlCvPubl(TlCvPubl $tlCvPubl): self
    {
        if (!$this->tlCvPubl->contains($tlCvPubl)) {
            $this->tlCvPubl[] = $tlCvPubl;
            $tlCvPubl->setIdPublication($this);
        }

        return $this;
    }

    public function removeTlCvPubl(TlCvPubl $tlCvPubl): self
    {
        if ($this->tlCvPubl->contains($tlCvPubl)) {
            $this->tlCvPubl->removeElement($tlCvPubl);
            // set the owning side to null (unless already changed)
            if ($tlCvPubl->getIdPublication() === $this) {
                $tlCvPubl->setIdPublication(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->getLbJustification();
    }


}
