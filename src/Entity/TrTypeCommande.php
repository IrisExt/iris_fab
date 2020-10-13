<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypeCommande
 *
 * @ORM\Table(name="tr_type_commande", uniqueConstraints={@ORM\UniqueConstraint(name="_code_tr_type_commande_pk", columns={"cd_commande"})})
 * @ORM\Entity
 */
class TrTypeCommande
{
    /**
     * @var string
     *
     * @ORM\Column(name="cd_commande", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_commande_cd_commande_seq", allocationSize=1, initialValue=1)
     */
    private $cdCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_commande", type="string", length=50, nullable=false)
     */
    private $lbCommande;

    public function getCdCommande(): ?string
    {
        return $this->cdCommande;
    }

    public function getLbCommande(): ?string
    {
        return $this->lbCommande;
    }

    public function setLbCommande(string $lbCommande): self
    {
        $this->lbCommande = $lbCommande;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLbCommande();
    }


}
