<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgCoordinationProj
 *
 * @ORM\Table(name="tg_coordination_proj", uniqueConstraints={@ORM\UniqueConstraint(name="tg_coordination_proj_pk", columns={"id_coordination"})}, indexes={@ORM\Index(name="tl_coordinateur_fk", columns={"cd_pays"}), @ORM\Index(name="projet_coordination_fk", columns={"id_projet"}), @ORM\Index(name="est_coordinateur_fk", columns={"id_organisme"}), @ORM\Index(name="personne_coordonne_fk", columns={"id_personne"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgCoordinationProjRepository")
 */
class TgCoordinationProj
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_coordination", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_coordination_proj_id_coordination_seq", allocationSize=1, initialValue=1)
     */
    private $idCoordination;

    /**
     * @var \TgOrganisme
     *
     * @ORM\ManyToOne(targetEntity="TgOrganisme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisme", referencedColumnName="id_organisme")
     * })
     */
    private $idOrganisme;

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
     * @var \TgProjet
     *
     * @ORM\ManyToOne(targetEntity="TgProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    /**
     * @var \TrPays
     *
     * @ORM\ManyToOne(targetEntity="TrPays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_pays", referencedColumnName="cd_pays")
     * })
     */
    private $cdPays;

    public function getIdCoordination(): ?int
    {
        return $this->idCoordination;
    }

    public function getIdOrganisme(): ?TgOrganisme
    {
        return $this->idOrganisme;
    }

    public function setIdOrganisme(?TgOrganisme $idOrganisme): self
    {
        $this->idOrganisme = $idOrganisme;

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

    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    public function getCdPays(): ?TrPays
    {
        return $this->cdPays;
    }

    public function setCdPays(?TrPays $cdPays): self
    {
        $this->cdPays = $cdPays;

        return $this;
    }


}
