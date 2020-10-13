<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tg_resume", indexes={@ORM\Index(name="projet_fk", columns={"id_projet"}), @ORM\Index(name="a_pour_langue_fk", columns={"id_langue"})})
 *
 * @ORM\Entity(repositoryClass="App\Repository\TgResumeRepository")
 */
class TgResume
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgProjet", inversedBy="tgResume")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TrLangue", inversedBy="tgResume")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_langue", referencedColumnName="id_langue")
     * })
     */
    private $idLangue;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_texte", type="text", nullable=true)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.resume.not_blank",
     *     groups={"formulaire", "bloc_BlResumeType"}
     * )
     */
    private $lbTexte;


    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    /**
     * @return TrLangue|null
     */
    public function getIdLangue(): ?TrLangue
    {
        return $this->idLangue;
    }

    /**
     * @param TrLangue|null $idLangue
     *
     * @return $this
     */
    public function setIdLangue(?TrLangue $idLangue): self
    {
        $this->idLangue = $idLangue;

        return $this;
    }

    /**
 * @return string|null
 */
    public function getLbTexte(): ?string
    {
        return $this->lbTexte;
    }

    /**
     * @param string $lbTexte
     *
     * @return $this
     */
    public function setLbTexte(string $lbTexte): self
    {
        $this->lbTexte = $lbTexte;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getIdProjet()->getLbAcro();
    }
}
