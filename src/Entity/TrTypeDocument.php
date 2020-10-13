<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrTypeDocument
 *
 * @ORM\Table(name="tr_type_document", uniqueConstraints={@ORM\UniqueConstraint(name="tr_type_document_pk", columns={"id_type_doc"})})
 * @ORM\Entity(repositoryClass="App\Repository\TrTypeDocRepository")
 */
class TrTypeDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_doc", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_type_document_id_type_doc_seq", allocationSize=1, initialValue=1)
     */
    private $idTypeDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_type_doc", type="string", length=50, nullable=false)
     */
    private $lbTypeDoc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="App\Entity\TgDocument", mappedBy="idTypeDoc", cascade={"persist"})
     */
    private $tgDocument;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgCourriel", mappedBy="idTypeDoc")
     */
    private $idCourriel;


    public function __construct()
    {
        $this->tgDocument = new ArrayCollection();
        $this->idCourriel = new ArrayCollection();
    }

    public function getIdTypeDoc(): ?int
    {
        return $this->idTypeDoc;
    }

    public function setIdTypeDoc(string $idTypeDoc): self
    {
        $this->idTypeDoc = $idTypeDoc;

        return $this;
    }

    public function getLbTypeDoc(): ?string
    {
        return $this->lbTypeDoc;
    }

    public function setLbTypeDoc(string $lbTypeDoc): self
    {
        $this->lbTypeDoc = $lbTypeDoc;

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
            $tgDocument->setIdProjet($this);
        }

        return $this;
    }

    public function removeTgDocument(TgDocument $tgDocument): self
    {
        if ($this->tgDocument->contains($tgDocument)) {
            $this->tgDocument->removeElement($tgDocument);
            // set the owning side to null (unless already changed)
            if ($tgDocument->getIdProjet() === $this) {
                $tgDocument->setIdProjet(null);
            }
        }

        return $this;
    }

    public function __toString() :string
    {
        return $this->getLbTypeDoc();
    }

}
