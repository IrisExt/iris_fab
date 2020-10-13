<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * TrCategorieErc
 *
 * @ORM\Table(name="tr_categorie_erc", uniqueConstraints={@ORM\UniqueConstraint(name="categorie_erc_pk", columns={"id_cat_erc"})})
 * @ORM\Entity(repositoryClass="App\Repository\TrCategorieRepository")
 */
class TrCategorieErc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_erc", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_categorie_erc_id_cat_erc_seq", allocationSize=1, initialValue=1)
     */
    private $idCatErc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_cat_erc", type="string", length=255, nullable=false)
     */
    private $lbCatErc;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="TrDiscErc", mappedBy="TrCategorieErc")
     */
    protected $TrDiscErcs;

    public function __construct()
    {
        $this->TrDiscErcs = new ArrayCollection();
    }


    public function getIdCatErc(): ?int
    {
        return $this->idCatErc;
    }

    public function getLbCatErc(): ?string
    {
        return $this->lbCatErc;
    }

    public function setLbCatErc(string $lbCatErc): self
    {
        $this->lbCatErc = $lbCatErc;

        return $this;
    }

    public function getTrDiscErcs(): ?Collection
    {
        return $this->TrDiscErcs;
    }

    public function setTrDiscErcs(?Collection $TrDiscErcs): self
    {
        $this->TrDiscErcs = $TrDiscErcs;

        return $this;
    }

    public function addTrDiscErc(TrDiscErc $trDiscErc): self
    {
        if (!$this->TrDiscErcs->contains($trDiscErc)) {
            $this->TrDiscErcs[] = $trDiscErc;
            $trDiscErc->setTrCategorieErc($this);
        }

        return $this;
    }

    public function removeTrDiscErc(TrDiscErc $trDiscErc): self
    {
        if ($this->TrDiscErcs->contains($trDiscErc)) {
            $this->TrDiscErcs->removeElement($trDiscErc);
            // set the owning side to null (unless already changed)
            if ($trDiscErc->getTrCategorieErc() === $this) {
                $trDiscErc->setTrCategorieErc(null);
            }
        }

        return $this;
    }
}
