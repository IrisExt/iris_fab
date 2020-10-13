<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrLangue
 *
 * @ORM\Table(name="tr_langue", uniqueConstraints={@ORM\UniqueConstraint(name="tr_langue_pk", columns={"id_langue"})})
 * @ORM\Entity(repositoryClass="App\Repository\TrLangueRepository")
 */
class TrLangue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_langue", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_langue_id_langue_seq", allocationSize=1, initialValue=1)
     */
    private $idLangue;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_langue", type="string", length=50, nullable=false)
     */
    private $lbLangue;

    /**
     * @var string
     *
     * @ORM\Column(name="cd_langue", type="string", length=3, nullable=true)
     */
    private $cdLangue;

    /**
     * @return string
     */
    public function getCdLangue(): string
    {
        return $this->cdLangue;
    }

    /**
     * @param string $cdLangue
     */
    public function setCdLangue(string $cdLangue): void
    {
        $this->cdLangue = $cdLangue;
    }

//    /**
//     * @var \Doctrine\Common\Collections\Collection
//     *
//     * @ORM\ManyToMany(targetEntity="TgProjet", inversedBy="idLangue")
//     * @ORM\JoinTable(name="tg_resume",
//     *   joinColumns={
//     *     @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
//     *   },
//     *   inverseJoinColumns={
//     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
//     *   }
//     * )
//     */
//    private $idProjet;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgAppelProj", mappedBy="idLangue")
     */
    private $idAppel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="TgResume", mappedBy="idLangue", cascade={"persist"})
     */
    private $tgResume;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="App\Entity\TgDocument", mappedBy="idLangue", cascade={"persist"})
     */
    private $tgDocument;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tgResume = new ArrayCollection();
        $this->idAppel = new ArrayCollection();
        $this->tgDocument = new ArrayCollection();
    }

    public function getIdLangue(): ?int
    {
        return $this->idLangue;
    }

    public function setIdLangue(string $idLangue): self
    {
        $this->idLangue = $idLangue;

        return $this;
    }

    public function getLbLangue(): ?string
    {
        return $this->lbLangue;
    }

    public function setLbLangue(string $lbLangue): self
    {
        $this->lbLangue = $lbLangue;

        return $this;
    }


    /**
     * @return Collection|TgResume[]
     */
    public function getTgResume(): Collection
    {
        return $this->tgResume;
    }

    public function addTgResume(TgResume $tgResume): self
    {
        if (!$this->tgResume->contains($tgResume)) {
            $this->tgResume[] = $tgResume;
            $tgResume->setIdLangue($this);
        }

        return $this;
    }

    public function removeTgResume(TgResume $tgResume): self
    {
        if ($this->tgResume->contains($tgResume)) {
            $this->tgResume->removeElement($tgResume);
            // set the owning side to null (unless already changed)
            if ($tgResume->getIdLangue() === $this) {
                $tgResume->setIdLangue(null);
            }
        }

        return $this;
    }

    public function __toString() :string
    {
        return $this->getLbLangue();
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
            $idAppel->addIdLangue($this);
        }

        return $this;
    }

    public function removeIdAppel(TgAppelProj $idAppel): self
    {
        if ($this->idAppel->contains($idAppel)) {
            $this->idAppel->removeElement($idAppel);
            $idAppel->removeIdLangue($this);
        }

        return $this;
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
            $tgDocument->setIdLangue($this);
        }

        return $this;
    }

    public function removeTgDocument(TgDocument $tgDocument): self
    {
        if ($this->tgDocument->contains($tgDocument)) {
            $this->tgDocument->removeElement($tgDocument);
            // set the owning side to null (unless already changed)
            if ($tgDocument->getIdLangue() === $this) {
                $tgDocument->setIdLangue(null);
            }
        }

        return $this;
    }

}
