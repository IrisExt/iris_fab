<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrPhase
 *
 * @ORM\Table(name="tr_phase")
 * @ORM\Entity(repositoryClass="App\Repository\TrPhaseRepository")
 */
class TrPhase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_phase_ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_phase_id_phase_ref_seq", allocationSize=1, initialValue=1)
     */
    private $idPhaseRef;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;


    public function getIdPhaseRef(): ?int
    {
        return $this->idPhaseRef;
    }

    public function getLbNom(): ?string
    {
        return $this->lbNom;
    }

    public function setLbNom(string $lbNom): self
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    public function __toString():string
    {
       return $this->getLbNom();
    }


}
