<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="tg_utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="tg_utilisateur_pk", columns={"id_personne", "id"})}, indexes={@ORM\Index(name="donne_fk", columns={"id_personne"})})
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email que vous avez indiqué est déja utilisé !"
 * )
 */
class User extends BaseUser implements EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

        /**
         * @var \TgPersonne
         *
         * @ORM\OneToOne(targetEntity="TgPersonne", inversedBy="Users", cascade={"persist"})
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="id_personne", referencedColumnName="id_personne")
         * })
         */
        private $idPersonne;

    /**
     * @var null|email
     *
     * @ORM\Column(name="identifiant_demande", type="string", length=100, nullable=true)
     *
     */
        private $identifiantDemande;

        /**
        *
        * @ORM\Column(name="cd_activation", type="string", length=100, nullable=true)
        *
        **/
        private $cdActivation;

    /**
     * @var DateTime|null
     *
     *
     * @ORM\Column(name="dh_envoi_code", type="datetime", nullable=true)
     *
     */
        private $dhEnvoiCode;

    /**
     * @var TgFavoris
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TgFavoris" , mappedBy="idUser")
     */
    private $idFavoris;

    public function __construct()
    {
        parent::__construct();

        $this->idFavoris = new ArrayCollection();
    }

       public function getDhEnvoiCode(): ?\DateTimeInterface
        {
            return $this->dhEnvoiCode;
        }

        public function setDhEnvoiCode(\DateTimeInterface $dhEnvoiCode): self
        {
            $this->dhEnvoiCode = $dhEnvoiCode;

            return $this;
        }

        public function getId(): ?int
        {
            return $this->id;
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

        public function getIdentifiantDemande(): ?string
        {
            return $this->identifiantDemande;
        }

        public function setIdentifiantDemande(?string $identifiantDemande): self
        {
            $this->identifiantDemande = $identifiantDemande;

            return $this;
        }

         public function getCdActivation(): ?string
         {
             return $this->cdActivation;
         }

         public function setCdActivation(?string $cdActivation): self
         {
             $this->cdActivation = $cdActivation;

             return $this;
         }

    /**
     * @return TgFavoris
     */
    public function getIdFavoris(): TgFavoris
    {
        return $this->idFavoris;
    }

    public function addIdFavoris(TgFavoris $idFavoris): self
    {
        if (!$this->idFavoris->contains($idFavoris)) {
            $this->idFavoris[] = $idFavoris;
            $idFavoris->setIdUser($this);
        }

        return $this;
    }

    public function removeIdFavoris(TgFavoris $idFavoris): self
    {
        if ($this->idFavoris->contains($idFavoris)) {
            $this->idFavoris->removeElement($idFavoris);
            // set the owning side to null (unless already changed)
            if ($idFavoris->getIdUser() === $this) {
                $idFavoris->setIdUser(null);
            }
        }

        return $this;
    }


    public function __toString(): string
           {

                return  $this->email;
           }

    public function isEqualTo(BaseUserInterface $user): bool
{
    if (!$user instanceof self) {
        return false;
    }

    if ($this->password !== $user->getPassword()) {
        return false;
    }

    if ($this->salt !== $user->getSalt()) {
        return false;
    }

    if ($this->username !== $user->getUsername()) {
        return false;
    }

    return true;
}
}