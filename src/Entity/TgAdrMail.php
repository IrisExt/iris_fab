<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgAdrMail
 *
 * @ORM\Table(name="tg_adr_mail", uniqueConstraints={@ORM\UniqueConstraint(name="adr_mail_pk", columns={"adr_mail", "adr_mail"})}, indexes={@ORM\Index(name="tl_pers_adr_mail_fk", columns={"id_personne"})})
 * @ORM\Entity
 */
class TgAdrMail
{

    /**
     * @var string
     *
     * @ORM\Column(name="adr_mail", type="string", length=40, nullable=false)
     * @ORM\Id
     *
     * @Assert\NotBlank(
     *     message="tgadrmail.adrmail.not_blank",
     *     groups={"ParticipantType"}
     *
     * )
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     * @Assert\Email(
     *     message = "{{ value }} n'est pas un email valide.",
     *    groups={"ParticipantType"}
     * )
     */
    private $adrMail;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_notification", type="boolean", nullable=true)
     */
    private $blNotification;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_valide", type="boolean", nullable=true, options={"comment"="enregistr�s invalide , les adresses sont valid�es sur retour de maiil"})
     */
    private $blValide;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="adr_pref", type="boolean", nullable=true)
     */
    private $adrPref;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="idAdrMail", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    /**
     * @var TlCopieMail
     * @ORM\OneToMany(targetEntity="App\Entity\TlCopieMail", mappedBy="adrMail", cascade={"persist"})
     */
    private $copieMail;

    /**
     * @var TlCourrielDest
     * @ORM\OneToMany(targetEntity="App\Entity\TlCourrielDest", mappedBy="adrMail", cascade={"persist"})
     */
    private $destMail;


    public function getAdrMail(): ?string
    {
        return $this->adrMail;
    }

    public function setAdrMail(string $adrMail): self
    {
        $this->adrMail = $adrMail;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAdrPref(): ?bool
    {
        return $this->adrPref;
    }

    /**
     * @param bool|null $adrPref
     */
    public function setAdrPref(?bool $adrPref): void
    {
        $this->adrPref = $adrPref;


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

    public function __toString() :string
    {
        return $this->adrMail;
    }

    public function getBlNotification(): ?bool
    {
        return $this->blNotification;
    }

    public function setBlNotification(?bool $blNotification): self
    {
        $this->blNotification = $blNotification;

        return $this;
    }

    public function getBlValide(): ?bool
    {
        return $this->blValide;
    }

    public function setBlValide(?bool $blValide): self
    {
        $this->blValide = $blValide;

        return $this;
    }

}
