<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TlLangPers
 *
 * @ORM\Table(name="tl_pers_org")
 * @ORM\Entity
 */
class TlPersOrg
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="tlPersOrg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgOrganisme", inversedBy="tlPersOrg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_organisme", referencedColumnName="id_organisme")
     * })
     */
    private $idOrganisme;



    /**
     * @var string
     * @ORM\Column(name="type_oraganisme", type="string", length=5, nullable=false)
     */
    private $typeOraganisme;


    /**
     * @var string|null
     * @ORM\Column(name="lb_service", type="string", length=255, nullable=true)
     */
    private $lbService;


    public function getTypeOraganisme(): ?string
    {
        return $this->typeOraganisme;
    }

    public function setTypeOraganisme(string $typeOraganisme): self
    {
        $this->typeOraganisme = $typeOraganisme;

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

    public function getIdOrganisme(): ?TgOrganisme
    {
        return $this->idOrganisme;
    }

    public function setIdOrganisme(?TgOrganisme $idOrganisme): self
    {
        $this->idOrganisme = $idOrganisme;

        return $this;
    }

    public function __toString(): string
    {
       return $this->getIdOrganisme()->getLbNomFr();
    }

    /**
     * @return string|null
     */
    public function getLbService(): ?string
    {
        return $this->lbService;
    }

    /**
     * @param string|null $lbService
     */
    public function setLbService(?string $lbService): void
    {
        $this->lbService = $lbService;
    }


}
