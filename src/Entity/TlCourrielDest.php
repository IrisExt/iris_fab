<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="tl_courriel_dest")
 */

class TlCourrielDest
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgCourriel", inversedBy="destMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_courriel", referencedColumnName="id_courriel")
     * })
     */
    private $idCourriel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgAdrMail", inversedBy="destMail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adr_mail", referencedColumnName="adr_mail")
     * })
     */

    private $adrMail;

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
}