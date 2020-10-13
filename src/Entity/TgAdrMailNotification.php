<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * TgAdrMailNotification
 *
 * @ORM\Table(name="tg_adr_mail_notification")
 * @ORM\Entity
 */
class TgAdrMailNotification
{
    /**
     * @var string
     *
     * @ORM\Column(name="adr_mail_notif", type="string", length=40, nullable=false)
     * @ORM\Id
     *
     */
    private $adrMailNotif;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="adrMailNotif")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_obsolete", type="boolean", nullable=true)
     */
    private $blObsolete;


    /**
     * @param $adrMailNotif
     * @return $this
     */
    public function setAdrMailNotif(string $adrMailNotif): self
    {
        $this->adrMailNotif = $adrMailNotif;
        return $this;
    }

    public function getAdrMailNotif(): ?string
    {
        return $this->adrMailNotif;
    }

    public function getBlObsolete(): ?bool
    {
        return $this->blObsolete;
    }

    public function setBlObsolete(?bool $blObsolete): self
    {
        $this->blObsolete = $blObsolete;

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

    public function __toString(): string
    {
        return $this->getAdrMailNotif();
    }

}