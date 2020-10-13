<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgTelephone
 *
 * @ORM\Table(name="tg_telephone", uniqueConstraints={@ORM\UniqueConstraint(name="no_telephone_pk", columns={"no_telephone", "no_telephone"})}, indexes={@ORM\Index(name="est_pour_tel_fk", columns={"id_personne"})})
 * @ORM\Entity
 */
class TgTelephone
{

    /**
     * @var string
     *
     * @ORM\Column(name="no_telephone", type="string", length=20, nullable=false)
     * @ORM\Id
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $noTelephone;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="bl_tel_pref", type="boolean", nullable=true)
     */
    private $blTelPref;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne", inversedBy="noTelephone", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

    public function getNoTelephone(): ?string
    {
        return $this->noTelephone;
    }

    public function setNoTelephone(?string $noTelephone): self
    {
        $this->noTelephone = $noTelephone;

        return $this;
    }

    public function getBlTelPref(): ?bool
    {
        return $this->blTelPref;
    }

    public function setBlTelPref(?bool $blTelPref): self
    {
        $this->blTelPref = $blTelPref;

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


}
