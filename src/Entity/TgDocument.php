<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgDocument
 *
 * @ORM\Table(name="tg_document")
 * @ORM\Entity(repositoryClass="App\Repository\TgDocumentRepository")
 */
class TgDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_doc", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_document_id_doc_seq", allocationSize=1, initialValue=1)
     */
    private $idDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fichier", type="string", length=255, nullable=true)
     *
     */
    private $lbNomFichier;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="TrTypeDocument", inversedBy="tgDocument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_doc", referencedColumnName="id_type_doc")
     * })
     */
    private $idTypeDoc;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="TgProjet", inversedBy="tgDocument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="TrLangue", inversedBy="tgDocument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="TgPhase", inversedBy="tgDocument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_phase", referencedColumnName="id_phase")
     * })
     */
    private $idPhase;

    /**
     * @var TlPjLibre
     * @ORM\OneToMany(targetEntity="App\Entity\TlPjLibre", mappedBy="idDoc", cascade={"persist"})
     * doc libre
     */
    private $docLibre;

    /**
     * @var TlDocCourriel
     * @ORM\OneToMany(targetEntity="App\Entity\TlDocCourriel", mappedBy="idDoc", cascade={"persist"})
     * doc libre
     */
    private $docCourriel;


    public function getIdDoc(): ?int
    {
        return $this->idDoc;
    }

    public function getLbNomFichier(): ?string
    {
        return $this->lbNomFichier;
    }

    public function setLbNomFichier(string $lbNomFichier): self
    {
        $this->lbNomFichier = $lbNomFichier;

        return $this;
    }

    public function getIdTypeDoc(): ?TrTypeDocument
    {
        return $this->idTypeDoc;
    }

    public function setIdTypeDoc(TrTypeDocument $idTypeDoc): self
    {
        $this->idTypeDoc = $idTypeDoc;

        return $this;
    }

    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    /**
     * @return TrLangue|null
     */
    public function getIdLangue(): ?TrLangue
    {
        return $this->idLangue;
    }

    /**
     * @param TrLangue|null $idLangue
     *
     * @return $this
     */
    public function setIdLangue(?TrLangue $idLangue): self
    {
        $this->idLangue = $idLangue;

        return $this;
    }

    /**
     * @return TrPhase|null
     */
    public function getIdPhase(): ?TgPhase
    {
        return $this->idPhase;
    }

    /**
     * @param TrPhase|null $idPhase
     *
     * @return $this
     */
    public function setIdPhase(?TgPhase $idPhase): self
    {
        $this->idPhase = $idPhase;

        return $this;
    }

    /**
     * @return TlPjLibre
     */
    public function getDocLibre(): TlPjLibre
    {
        return $this->docLibre;
    }

    /**
     * @param TlPjLibre $docLibre
     */
    public function setDocLibre(TlPjLibre $docLibre): void
    {
        $this->docLibre = $docLibre;
    }

    /**
     * @return TlDocCourriel
     */
    public function getDocCourriel(): TlDocCourriel
    {
        return $this->docCourriel;
    }

    /**
     * @param TlDocCourriel $docCourriel
     */
    public function setDocCourriel(TlDocCourriel $docCourriel): void
    {
        $this->docCourriel = $docCourriel;
    }

}
