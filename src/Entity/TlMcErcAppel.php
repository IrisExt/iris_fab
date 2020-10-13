<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_mc_erc_appel")
 **/
class TlMcErcAppel
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgAppelProj", inversedBy="tlMcErcAppel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_appel", referencedColumnName="id_appel")
     * })
     */
    private $idAppel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgMotCleErc", inversedBy="tlMcErcAppel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mc_erc", referencedColumnName="id_mc_erc")
     * })
     */
    private $idMcErc;


    public function getIdAppel(): ?TgAppelProj
    {
        return $this->idAppel;
    }

    public function setIdAppel(?TgAppelProj $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }

    public function getIdMcErc(): ?TgMotCleErc
    {
        return $this->idMcErc;
    }

    public function setIdMcErc(?TgMotCleErc $idMcErc): self
    {
        $this->idMcErc = $idMcErc;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getIdMcErc()->getLbNomFr();
    }

}