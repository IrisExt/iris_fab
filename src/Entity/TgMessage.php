<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TgMessage
 *
 * @ORM\Table(name="tg_message", uniqueConstraints={@ORM\UniqueConstraint(name="tg2_message_pk", columns={"id_message"})}, indexes={@ORM\Index(name="est_dest_fk", columns={"destinataire"}), @ORM\Index(name="pour_fk", columns={"id_comite"}), @ORM\Index(name="est_emetteur_fk", columns={"emetteur"})})
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class TgMessage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_message", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_message_id_message_seq", allocationSize=1, initialValue=1)
     */
    private $idMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dh_envoi", type="datetime", nullable=false)
     */
    private $dhEnvoi;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="string", length=200, nullable=false)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $texte;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="destinataire", referencedColumnName="id_personne")
     * })
     */
    private $destinataire;

    /**
     * @var \TgPersonne
     *
     * @ORM\ManyToOne(targetEntity="TgPersonne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emetteur", referencedColumnName="id_personne")
     * })
     */
    private $emetteur;

    /**
     * @var \TgComite
     *
     * @ORM\ManyToOne(targetEntity="TgComite" , inversedBy="idMessage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_comite", referencedColumnName="id_comite")
     * })
     */
    private $idComite;


    /**
     * @var TgParticipation
     *
     * @ORM\ManyToOne(targetEntity="TgParticipation" , inversedBy="idMessage", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_participation", referencedColumnName="id_participation")
     * })
     */
    private $idParticipation;

    public function getIdMessage(): ?int
    {
        return $this->idMessage;
    }

    public function getDhEnvoi(): ?\DateTimeInterface
    {
        return $this->dhEnvoi;
    }

    public function setDhEnvoi(\DateTimeInterface $dhEnvoi): self
    {
        $this->dhEnvoi = $dhEnvoi;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getDestinataire(): ?TgPersonne
    {
        return $this->destinataire;
    }

    public function setDestinataire(?TgPersonne $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getEmetteur(): ?TgPersonne
    {
        return $this->emetteur;
    }

    public function setEmetteur(?TgPersonne $emetteur): self
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    public function getIdComite(): ?TgComite
    {
        return $this->idComite;
    }

    public function setIdComite(?TgComite $idComite): self
    {
        $this->idComite = $idComite;

        return $this;
    }

    public function __toString()
    {
        return $this->getEmetteur(). '->' .$this->getDestinataire(). ': ' . $this->getTexte();
    }

    public function getIdParticipation(): ?TgParticipation
    {
        return $this->idParticipation;
    }

    public function setIdParticipation(?TgParticipation $idParticipation): self
    {
        $this->idParticipation = $idParticipation;

        return $this;
    }


}
