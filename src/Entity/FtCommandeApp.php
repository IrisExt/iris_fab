<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * FtCommandeApp
 *
 * @ORM\Table(name="ft_commande_app", indexes={@ORM\Index(name="pour_type_commande_app_fk", columns={"cd_commande"}), @ORM\Index(name="commande_a_pour_personne_fk", columns={"id_personne"}), @ORM\Index(name="commande_a_pour_projet_fk", columns={"id_projet"})})
 * @ORM\Entity
 */
class FtCommandeApp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_fr_command_app", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ft_commande_app_id_fr_command_app_seq", allocationSize=1, initialValue=1)
     */
    private $idFrCommandApp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dh_commande", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dhCommande;

    /**
     * @var string|null
     *
     * @ORM\Column(name="txt_commentaire", type="text", nullable=true)
     */
    private $txtCommentaire;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
     * })
     */
    private $idPersonne;

//    /**
//     * @var \TgUtilisateur
//     *
//     * @ORM\ManyToOne(targetEntity="TgUtilisateur")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
//     * })
//     */
//    private $idUtilisateur;

    /**
     * @var \TrTypeCommande
     *
     * @ORM\ManyToOne(targetEntity="TrTypeCommande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cd_commande", referencedColumnName="cd_commande")
     * })
     */
    private $cdCommande;

    /**
     * @var \TgProjet
     *
     * @ORM\ManyToOne(targetEntity="TgProjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id_projet")
     * })
     */
    private $idProjet;

    public function __construct()
    {
        $this->dhCommande = new \DateTime();
    }


    public function getIdFrCommandApp(): ?string
    {
        return $this->idFrCommandApp;
    }

    public function getDhCommande(): ?\DateTimeInterface
    {
        return $this->dhCommande;
    }

    public function setDhCommande(\DateTimeInterface $dhCommande): self
    {
        $this->dhCommande = $dhCommande;

        return $this;
    }

    public function getTxtCommentaire(): ?string
    {
        return $this->txtCommentaire;
    }

    public function setTxtCommentaire(?string $txtCommentaire): self
    {
        $this->txtCommentaire = $txtCommentaire;

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

    public function getCdCommande(): ?TrTypeCommande
    {
        return $this->cdCommande;
    }

    public function setCdCommande(?TrTypeCommande $cdCommande): self
    {
        $this->cdCommande = $cdCommande;

        return $this;
    }

    public function getIdProjet(): ?TgProjet
    {
        return $this->idProjet;
    }

    public function setIdProjet(?TgProjet $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }


}
