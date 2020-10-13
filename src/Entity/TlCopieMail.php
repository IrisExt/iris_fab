<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="tl_copie_mail")
 */

class TlCopieMail
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgCourriel", inversedBy="copieMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_courriel", referencedColumnName="id_courriel")
     * })
     */
    private $idCourriel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgAdrMail", inversedBy="copieMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adr_mail", referencedColumnName="adr_mail")
     * })
     */

    private $adrMail;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_cache", type="boolean", nullable=true)
     */
    private $blCache;

    /**
     * @return mixed
     */
    public function getIdCourriel()
    {
        return $this->idCourriel;
    }

    /**
     * @param mixed $idCourriel
     */
    public function setIdCourriel($idCourriel): void
    {
        $this->idCourriel = $idCourriel;
    }

    /**
     * @return mixed
     */
    public function getAdrMail()
    {
        return $this->adrMail;
    }

    /**
     * @param mixed $adrMail
     */
    public function setAdrMail($adrMail): void
    {
        $this->adrMail = $adrMail;
    }

    /**
     * @return bool|null
     */
    public function getBlCache(): ?bool
    {
        return $this->blCache;
    }

    /**
     * @param bool|null $blCache
     */
    public function setBlCache(?bool $blCache): void
    {
        $this->blCache = $blCache;
    }

    public function __toString(): string
    {
        return $this->adrMail;
    }

}