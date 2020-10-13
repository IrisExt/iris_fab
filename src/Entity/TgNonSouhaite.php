<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgNonSouhaite
 *
 * @ORM\Table(name="tg_non_souhaite", uniqueConstraints={@ORM\UniqueConstraint(name="tg_non_souhaite_pk", columns={"id_non_souhaite"})})
 * @ORM\Entity(repositoryClass="App\Repository\TgNonSouhaiteRepository")
 */
class TgNonSouhaite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_non_souhaite", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_non_souhaite_id_non_souhaite_seq", allocationSize=1, initialValue=1)
     */
    private $idNonSouhaite;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.nnsouhaiteNom.not_blank",
     *     groups={"formulaire", "NonSouhaiteType"}
     *
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.nnsouhaite.min_length",
     *      maxMessage = "tgprojet.nnsouhaite.max_length",
     *      groups={"NonSouhaiteType"}
     *
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_prenom", type="string", length=50, nullable=false, options={"comment"="Prénom d'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.nnsouhaite.min_length",
     *      maxMessage = "tgprojet.nnsouhaite.max_length",
     *      groups={"NonSouhaiteType"}
     * )
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="organisme", type="string", length=50, nullable=true)
     * @Assert\Length(
     *       min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.nnsouhaite.min_length",
     *      maxMessage = "tgprojet.nnsouhaite.max_length",
     *      groups={"NonSouhaiteType"}
     * )
     */
    private $organisme;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_courriel", type="string", length=50, nullable=true)
     *
     * @Assert\NotBlank(
     *     message="tgprojet.nnsouhaiteEmail.not_blank",
     *     groups={"formulaire", "NonSouhaiteType"}
     *
     * )
     * @Assert\Email(
     *     message = "{{ value }} n'est pas un email valide.",
     *    groups={"NonSouhaiteType"}
     * )
     */
    private $courriel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_motif", type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *       min = 2,
     *      max = 50,
     *      minMessage = "tgprojet.nnsouhaite.min_length",
     *      maxMessage = "tgprojet.nnsouhaite.max_length",
     *      groups={"NonSouhaiteType"}
     * )
     */
    private $lbMotif;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgProjet", mappedBy="idNonSouhaite")
     */
    private $idProjet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProjet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdNonSouhaite(): ?int
    {
        return $this->idNonSouhaite;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(?string $organisme): self
    {
        $this->organisme = $organisme;

        return $this;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(?string $courriel): self
    {
        $this->courriel = $courriel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbMotif(): ?string
    {
        return $this->lbMotif;
    }

    /**
     * @param string|null $lbMotif
     */
    public function setLbMotif(?string $lbMotif): void
    {
        $this->lbMotif = $lbMotif;
    }


    /**
     * @return Collection|TgProjet[]
     */
    public function getIdProjet(): Collection
    {
        return $this->idProjet;
    }

    public function addIdProjet(TgProjet $idProjet): self
    {
        if (!$this->idProjet->contains($idProjet)) {
            $this->idProjet[] = $idProjet;
            $idProjet->addIdNonSouhaite($this);
        }

        return $this;
    }

    public function removeIdProjet(TgProjet $idProjet): self
    {
        if ($this->idProjet->contains($idProjet)) {
            $this->idProjet->removeElement($idProjet);
            $idProjet->removeIdNonSouhaite($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
    }

}
