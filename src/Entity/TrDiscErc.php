<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrDiscErc
 *
 * @ORM\Table(name="tr_disc_erc", uniqueConstraints={@ORM\UniqueConstraint(name="discipline_erc_pk", columns={"id_disc_erc"})}, indexes={@ORM\Index(name="discipline_fait_partie_fk", columns={"id_cat_erc"})})
 * @ORM\Entity
 */
class TrDiscErc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_disc_erc", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_disc_erc_id_disc_erc_seq", allocationSize=1, initialValue=1)
     */
    private $idDiscErc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_disc_erc", type="string", length=50, nullable=false)
     */
    private $lbDiscErc;

    /**
     * @var \TrCategorieErc
     *
     * @ORM\ManyToOne(targetEntity="TrCategorieErc", inversedBy="TrDiscErcs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_erc", referencedColumnName="id_cat_erc")
     * })
     */
    private $TrCategorieErc;

    /**
     * @var Collection
     *  @ORM\OneToMany(targetEntity="TgMotCleErc", mappedBy="trMcErcs")
     */
    protected $TgMcErcs;

    public function __construct()
    {
        $this->TgMcErcs = new ArrayCollection();
    }

    public function getIdDiscErc(): ?int
    {
        return $this->idDiscErc;
    }

    public function getLbDiscErc(): ?string
    {
        return $this->lbDiscErc;
    }

    public function setLbDiscErc(string $lbDiscErc): self
    {
        $this->lbDiscErc = $lbDiscErc;

        return $this;
    }

    public function getTrCategorieErc(): ?TrCategorieErc
    {
        return $this->TrCategorieErc;
    }

    public function setTrCategorieErc(?TrCategorieErc $TrCategorieErc): self
    {
        $this->TrCategorieErc = $TrCategorieErc;

        return $this;
    }

    public function getTgMcErcs(): ?Collection
    {
        return $this->TgMcErcs;
    }

    public function addTgMcErc(TgMotCleErc $tgMcErc): self
    {
        if (!$this->TgMcErcs->contains($tgMcErc)) {
            $this->TgMcErcs[] = $tgMcErc;
            $tgMcErc->setTrDiscErc($this);
        }

        return $this;
    }

    public function removeTgMcErc(TgMotCleErc $tgMcErc): self
    {
        if ($this->TgMcErcs->contains($tgMcErc)) {
            $this->TgMcErcs->removeElement($tgMcErc);
            // set the owning side to null (unless already changed)
            if ($tgMcErc->getTrDiscErc() === $this) {
                $tgMcErc->setTrDiscErc(null);
            }
        }

        return $this;
    }

}
