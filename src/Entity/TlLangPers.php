<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TlLangPers
 *
 * @ORM\Table(name="tl_lang_pers")
 * @ORM\Entity
 */
class TlLangPers
{

    /**
     * @var int
     *
     * @ORM\Column(name="id_lang_pers", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_lang_pers_id_lang_pers_seq", allocationSize=1, initialValue=1)
     */
    private $idLangPers;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="cd_pratique", type="boolean", nullable=true, options={"comment"="Code précisant le niveau de pratique de la langue:lu, parlé, écrite.."})
     */
    private $cdPratique;



    /**
     * @var \TrNiveauLangue
     *
     * @ORM\ManyToOne(targetEntity="TrNiveauLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_niveau", referencedColumnName="id_niveau")
     * })
     */
    private $idNiveau;

//    /**
//     * @var \TgPersonne
//     *
//     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="idLangPers")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
//     * })
//     */
//    private $idPersonne;

    /**
     * @var \TrLangue
     *

     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TrLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    public function getIdLangPers(): ?string
    {
        return $this->idLangPers;
    }

    public function getCdPratique(): ?bool
    {
        return $this->cdPratique;
    }

    public function setCdPratique(?bool $cdPratique): self
    {
        $this->cdPratique = $cdPratique;

        return $this;
    }

    public function getIdNiveau(): ?TrNiveauLangue
    {
        return $this->idNiveau;
    }

    public function setIdNiveau(?TrNiveauLangue $idNiveau): self
    {
        $this->idNiveau = $idNiveau;

        return $this;
    }

    public function getIdLangue(): ?TrLangue
    {
        return $this->idLangue;
    }

    public function setIdLangue(?TrLangue $idLangue): self
    {
        $this->idLangue = $idLangue;

        return $this;
    }

//    public function getIdLangPers(): ?int
//    {
//        return $this->idLangPers;
//    }
//
//    public function getCdPratique(): ?bool
//    {
//        return $this->cdPratique;
//    }
//
//    public function setCdPratique(?bool $cdPratique): self
//    {
//        $this->cdPratique = $cdPratique;
//
//        return $this;
//    }
//
//    public function getIdNiveau(): ?TrNiveauLangue
//    {
//        return $this->idNiveau;
//    }
//
//    public function setIdNiveau(?TrNiveauLangue $idNiveau): self
//    {
//        $this->idNiveau = $idNiveau;
//
//        return $this;
//    }
//
//    public function getIdPersonne(): ?TgPersonne
//    {
//        return $this->idPersonne;
//    }
//
//    public function setIdPersonne(?TgPersonne $idPersonne): self
//    {
//        $this->idPersonne = $idPersonne;
//
//        return $this;
//    }
//
//    public function getIdLangue(): ?TrLangue
//    {
//        return $this->idLangue;
//    }
//
//    public function setIdLangue(?TrLangue $idLangue): self
//    {
//        $this->idLangue = $idLangue;
//
//        return $this;
//    }
//
//    public function __toString(): string
//    {
//        return $this->getIdLangue()->getLbLangue();
//    }


}
