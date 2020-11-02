<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgPhase
 *
 * @ORM\Table(name="tg_phase", uniqueConstraints={@ORM\UniqueConstraint(name="tg_phase_pk", columns={"id_phase"})})
 * @ORM\Entity(repositoryClass="App\Repository\PhaseRepository")
 */
class TgPhase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_phase", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_phase_id_phase_seq", allocationSize=1, initialValue=1)
     */
    private $idPhase;


    /**
     * @var TrPhase
     *
     * @ORM\ManyToOne(targetEntity="TrPhase" )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase_ref", referencedColumnName="id_phase_ref")
     * })
     */
    private $idPhaseRef;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgHabilitation", inversedBy="idPhase")
     * @ORM\JoinTable(name="tl_hab_phase",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_phase", referencedColumnName="id_phase")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_habilitation", referencedColumnName="id_habilitation")
     *   }
     * )
     */
    private $idHabilitation;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_rendu_expertise", type="date", nullable=true)
     */
    private $dhRenduExpertise;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_rendu_eval_rl", type="date", nullable=true)
     */
    private $dhRenduEvalRL;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dh_rendu_rapport", type="date", nullable=true)
     */
    private $dhRenduRapport;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TgNiveauPhase", mappedBy="idPhase", cascade={"persist"})
     */
    private $idNiveauPhase;

    /**
     * @var TlFormulaireAppel
     * @ORM\OneToMany(targetEntity="App\Entity\TlFormulaireAppel", mappedBy="idPhase", cascade={"persist"})
     */
    private $tlFormulaireAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="App\Entity\TgDocument", mappedBy="idPhase", cascade={"persist"})
     */
    private $tgDocument;

    public function __construct()
    {
        $this->idHabilitation = new ArrayCollection();
        $this->idNiveauPhase = new ArrayCollection();
        $this->tlFormulaireAppel = new ArrayCollection();
        $this->tgDocument = new ArrayCollection();
    }

    public function getIdPhase(): ?int
    {
        return $this->idPhase;
    }

    public function getIdPhaseRef(): ?TrPhase
    {
        return $this->idPhaseRef;
    }

    public function setIdPhaseRef(?TrPhase $idPhaseRef): self
    {
        $this->idPhaseRef = $idPhaseRef;

        return $this;
    }

    /**
     * @return Collection|TgHabilitation[]
     */
    public function getIdHabilitation(): Collection
    {
        return $this->idHabilitation;
    }

    public function addIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if (!$this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation[] = $idHabilitation;
        }

        return $this;
    }

    public function removeIdHabilitation(TgHabilitation $idHabilitation): self
    {
        if ($this->idHabilitation->contains($idHabilitation)) {
            $this->idHabilitation->removeElement($idHabilitation);
        }

        return $this;
    }

    /**
     * @return Collection|TgPhase[]
     */
    public function getIdNiveauPhase(): Collection
    {
        return $this->idNiveauPhase;
    }

    public function addIdNiveauPhase(TgPhase $idNiveauPhase): self
    {
        if (!$this->idNiveauPhase->contains($idNiveauPhase)) {
            $this->idNiveauPhase[] = $idNiveauPhase;
            $idNiveauPhase->setIdNiveauPhase($this);
        }

        return $this;
    }

    public function removeIdNiveauPhase(TgPhase $idNiveauPhase): self
    {
        if ($this->idNiveauPhase->contains($idNiveauPhase)) {
            $this->idNiveauPhase->removeElement($idNiveauPhase);
            // set the owning side to null (unless already changed)
            if ($idNiveauPhase->getIdNiveauPhase() === $this) {
                $idNiveauPhase->setIdNiveauPhase(null);
            }
        }

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
            $tlFormulaireAppel->setIdPhase($this);
        }

        return $this;
    }

    public function removeTlFormulaireAppel(TlFormulaireAppel $tlFormulaireAppel): self
    {
        if ($this->tlFormulaireAppel->contains($tlFormulaireAppel)) {
            $this->tlFormulaireAppel->removeElement($tlFormulaireAppel);
            // set the owning side to null (unless already changed)
            if ($tlFormulaireAppel->getIdPhase() === $this) {
                $tlFormulaireAppel->setIdPhase(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getIdPhaseRef()->getLbNom();
    }

    /**
     * @return Collection|TgDocument[]
     */
    public function getTgDocument(): Collection
    {
        return $this->tgDocument;
    }

    public function addTgDocument(TgDocument $tgDocument): self
    {
        if (!$this->tgDocument->contains($tgDocument)) {
            $this->tgDocument[] = $tgDocument;
            $tgDocument->setIdPhase($this);
        }

        return $this;
    }

    public function removeTgDocument(TgDocument $tgDocument): self
    {
        if ($this->tgDocument->contains($tgDocument)) {
            $this->tgDocument->removeElement($tgDocument);
            // set the owning side to null (unless already changed)
            if ($tgDocument->getIdPhase() === $this) {
                $tgDocument->setIdPhase(null);
            }
        }

        return $this;
    }

    public function getDhRenduExpertise(): ?\DateTimeInterface
    {
        return $this->dhRenduExpertise;
    }

    public function setDhRenduExpertise(?\DateTimeInterface $dhRenduExpertise): self
    {
        $this->dhRenduExpertise = $dhRenduExpertise;

        return $this;
    }

    public function getDhRenduEvalRL(): ?\DateTimeInterface
    {
        return $this->dhRenduEvalRL;
    }

    public function setDhRenduEvalRL(?\DateTimeInterface $dhRenduEvalRL): self
    {
        $this->dhRenduEvalRL = $dhRenduEvalRL;

        return $this;
    }

    public function getDhRenduRapport(): ?\DateTimeInterface
    {
        return $this->dhRenduRapport;
    }

    public function setDhRenduRapport(?\DateTimeInterface $dhRenduRapport): self
    {
        $this->dhRenduRapport = $dhRenduRapport;

        return $this;
    }
}
