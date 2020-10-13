<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrDureeProjet
 *
 * @ORM\Table(name="tr_duree_projet", indexes={@ORM\Index(name="IDX_92201B87EFB5928D", columns={"id_appel"})})
 * @ORM\Entity
 */
class TrDureeProjet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_duree", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_duree_projet_id_duree_seq", allocationSize=1, initialValue=1)
     */
    private $idDuree;

    /**
     * @var int
     *
     * @ORM\Column(name="no_duree", type="smallint", nullable=false)
     */
    private $noDuree;

    /**
     * @var \TgAppelProj
     *
     * @ORM\ManyToOne(targetEntity="TgAppelProj")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    public function getIdDuree(): ?int
    {
        return $this->idDuree;
    }

    public function getNoDuree(): ?int
    {
        return $this->noDuree;
    }

    public function setNoDuree(int $noDuree): self
    {
        $this->noDuree = $noDuree;

        return $this;
    }

    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }


}
