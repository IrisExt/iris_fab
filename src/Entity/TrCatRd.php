<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrCatRd
 *
 * @ORM\Table(name="tr_cat_rd", uniqueConstraints={@ORM\UniqueConstraint(name="tr_cat_rd_pk", columns={"id_cat_rd"})})
 * @ORM\Entity
 */
class TrCatRd
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_rd", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_cat_rd_id_cat_rd_seq", allocationSize=1, initialValue=1)
     */
    private $idCatRd;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_categorie", type="string", length=50, nullable=false)
     */
    private $lbCatRd;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgAppelProj", inversedBy="idCatRd")
     * @ORM\JoinTable(name="tl_catrd_appel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_cat_rd", referencedColumnName="id_cat_rd")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     *   }
     * )
     */
    private $idAppel;

    public function __construct()
    {
        $this->idAppel = new ArrayCollection();
    }


    public function getIdCatRd(): ?int
    {
        return $this->idCatRd;
    }

    public function getLbCatRd(): ?string
    {
        return $this->lbCatRd;
    }

    public function setLbCatRd(string $lbCatRd): self
    {
        $this->lbCatRd = $lbCatRd;

        return $this;
    }

    public function __toString()
    {
        return $this->getLbCatRd();
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
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
        }

        return $this;
    }

}
