<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgFavoris
 *
 * @ORM\Table(name="tg_favoris", uniqueConstraints={@ORM\UniqueConstraint(name="tg_favoris_pk", columns={"id_favoris"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgFavorisRepository")
 */
class TgFavoris
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_favoris", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_favoris_id_favoris_seq", allocationSize=1, initialValue=1)
     */
    private $idFavoris;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNom;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="idFavoris", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="parametre", type="text", nullable=true)
     */
    private $parametre;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_favoris", type="string", length=3, nullable=false)
     */
    private $typFavoris;

    /**
     * @return string
     */
    public function getTypFavoris(): string
    {
        return $this->typFavoris;
    }

    /**
     * @param string $typFavoris
     */
    public function setTypFavoris(string $typFavoris): void
    {
        $this->typFavoris = $typFavoris;
    }

    /**
     * @return int
     */
    public function getIdFavoris(): int
    {
        return $this->idFavoris;
    }

    /**
     * @param int $idFavoris
     */
    public function setIdFavoris(int $idFavoris): void
    {
        $this->idFavoris = $idFavoris;
    }

    /**
     * @return string
     */
    public function getLbNom(): string
    {
        return $this->lbNom;
    }

    /**
     * @param string $lbNom
     */
    public function setLbNom(string $lbNom): void
    {
        $this->lbNom = $lbNom;
    }

    /**
     * @return \User
     */
    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    /**
     * @param \User $idUser
     */
    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return string
     */
    public function getParametre(): string
    {
        return $this->parametre;
    }

    /**
     * @param string $parametre
     */
    public function setParametre(string $parametre): void
    {
        $this->parametre = $parametre;
    }

    public function __toString(): string
    {
        return $this->getLbNom();
    }

}
