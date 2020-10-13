<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgCompetenceLangue
 *
 * @ORM\Table(name="tg_competence_langue", uniqueConstraints={@ORM\UniqueConstraint(name="tl_lang_pers_pk", columns={"id_pratique_langue"})}, indexes={@ORM\Index(name="langue_parle_fk", columns={"niveau_parle"}), @ORM\Index(name="a_pour_maitrise_en_langue_fk", columns={"niveau_ecrit"}), @ORM\Index(name="personne_pratique_fk", columns={"id_personne"}), @ORM\Index(name="pratique_langue_fk", columns={"id_langue"}), @ORM\Index(name="langue_lue_fk", columns={"niveau_lu"})})
 * @ORM\Entity
 */
class TgCompetenceLangue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pratique_langue", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_competence_langue_id_pratique_langue_seq", allocationSize=1, initialValue=1)
     */
    private $idPratiqueLangue;

    /**
     * @var \TrNiveauLangue
     *
     * @ORM\ManyToOne(targetEntity="TrNiveauLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="niveau_ecrit", referencedColumnName="id_niveau")
     * })
     */
    private $niveauEcrit;

    /**
     * @var \TrNiveauLangue
     *
     * @ORM\ManyToOne(targetEntity="TrNiveauLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="niveau_lu", referencedColumnName="id_niveau")
     * })
     */
    private $niveauLu;

    /**
     * @var \TrNiveauLangue
     *
     * @ORM\ManyToOne(targetEntity="TrNiveauLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="niveau_parle", referencedColumnName="id_niveau")
     * })
     */
    private $niveauParle;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var \TrLangue
     *
     * @ORM\ManyToOne(targetEntity="TrLangue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    public function getIdPratiqueLangue(): ?string
    {
        return $this->idPratiqueLangue;
    }

    public function getNiveauEcrit(): ?TrNiveauLangue
    {
        return $this->niveauEcrit;
    }

    public function setNiveauEcrit(?TrNiveauLangue $niveauEcrit): self
    {
        $this->niveauEcrit = $niveauEcrit;

        return $this;
    }

    public function getNiveauLu(): ?TrNiveauLangue
    {
        return $this->niveauLu;
    }

    public function setNiveauLu(?TrNiveauLangue $niveauLu): self
    {
        $this->niveauLu = $niveauLu;

        return $this;
    }

    public function getNiveauParle(): ?TrNiveauLangue
    {
        return $this->niveauParle;
    }

    public function setNiveauParle(?TrNiveauLangue $niveauParle): self
    {
        $this->niveauParle = $niveauParle;

        return $this;
    }

    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

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


}
