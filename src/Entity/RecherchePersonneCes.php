<?php
namespace App\Entity;

class RecherchePersonneCes {

    /**
     * @var string|null
     */
    private $personne;

    /**
     * @var string|null
     */
    private $motcle;

    /**
     * @var string|null
     */
    private $organisme;

    /**
     * @var string|null
     */
    private $genre;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @return string|null
     */
    public function getPersonne(): ?string
    {
        return $this->personne;
    }


    /**
     * @param string|null $personne
     */
    public function setPersonne(?string $personne): void
    {
        $this->personne = $personne;
    }

    /**
     * @return string|null
     */
    public function getMotcle(): ?string
    {
        return $this->motcle;
    }

    /**
     * @param string|null $motcle
     */
    public function setMotcle(?string $motcle): void
    {
        $this->motcle = $motcle;
    }

    /**
     * @return string|null
     */
    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    /**
     * @param string|null $organisme
     */
    public function setOrganisme(?string $organisme): void
    {
        $this->organisme = $organisme;
    }

    /**
     * @return string|null
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * @param string|null $organisme
     */
    public function setGenre(?string $genre): void
    {
        $this->organisme = $genre;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $organisme
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }


}