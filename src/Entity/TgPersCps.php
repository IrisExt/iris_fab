<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TgPersCps
 *
 * @ORM\Table(name="tg_pers_cps", uniqueConstraints={@ORM\UniqueConstraint(name="tg_pers_cps_pk", columns={"id_pers_cps"})})
 * @ORM\Entity(repositoryClass="App\Repository\PersCpsRepository")
 */
class TgPersCps
{

    /**
     * @var int
     * @ORM\Column(name="id_pers_cps", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_pers_cps_id_pers_cps_seq", allocationSize=1, initialValue=1)
     */

    private $idPersCps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_web_perso", type="string", length=100, nullable=true, options={"comment"="Site web peson de la personne saisi par le cps"})
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     * */
    private $lbWebPerso;

    /**
     * @var bool
     *
     * @ORM\Column(name="bl_sexe", type="boolean", nullable=true)
     */
    private $blSexe;

    /**
     * @var \TrGenre
     *
     * @ORM\ManyToOne(targetEntity="TrGenre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genre", referencedColumnName="id_genre")
     * })
     */
    private $idGenre;


    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_langue", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     */
    private $lbLangue;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Le titire est limité à {{ limit }} caractères"
     * )
     */
    private $lbNomFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_prenom", type="string", length=50, nullable=true, options={"comment"="Prénom d'usage de la personne (créé provisoirement en attendant le rapprochement du référentiel personne 14/05/2019)"})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     */
    private $lbPrenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_adr_mail", type="string", nullable=true)
     */
    private $lbAdrMail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_ville_heberg", type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     */
    private $lbVilleHeberg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lb_organisme", type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La déscription est limité à {{ limit }} caractères"
     * )
     */
    private $lbOrganisme;

    /**
     * @var TgPersonne
     *
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TgPersonne" ,mappedBy="idPersCps", cascade={"persist", "remove"})
     */
    private $idPersonne;

    /**
     * @return int
     */
    public function getIdPersCps(): ?int
    {

        return $this->idPersCps;
    }

    /**
     * @param int $idPersCps
     */
    public function setIdPersCps(int $idPersCps): self
    {
        $this->idPersCps = $idPersCps;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbWebPerso(): ?string
    {
        return $this->lbWebPerso;
    }

    /**
     * @param string|null $lbWebPerso
     */
    public function setLbWebPerso(?string $lbWebPerso): self
    {
        $this->lbWebPerso = $lbWebPerso;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBlSexe(): ?bool
    {
        return $this->blSexe;

    }

    /**
     * @param bool $blSexe
     */
    public function setBlSexe(bool $blSexe): self
    {
        $this->blSexe = $blSexe;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getLbLangue(): ?string
    {
        return $this->lbLangue;
    }

    /**
     * @param string|null $lbLangue
     */
    public function setLbLangue(?string $lbLangue): self
    {
        $this->lbLangue = $lbLangue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbNomFr(): ?string
    {
        return $this->lbNomFr;
    }

    /**
     * @param string|null $lbNomFr
     */
    public function setLbNomFr(?string $lbNomFr): self
    {
        $this->lbNomFr = $lbNomFr;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbPrenom(): ?string
    {
        return $this->lbPrenom;
    }

    /**
     * @param string|null $lbPrenom
     */
    public function setLbPrenom(?string $lbPrenom): self
    {
        $this->lbPrenom = $lbPrenom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbAdrMail(): ?string
    {
        return $this->lbAdrMail;
    }

    /**
     * @param string|null $lbAdrMail
     */
    public function setLbAdrMail(?string $lbAdrMail): self
    {
        $this->lbAdrMail = $lbAdrMail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLbVilleHeberg(): ?string
    {
        return $this->lbVilleHeberg;
    }

    /**
     * @param string|null $lbVilleHeberg
     */
    public function setLbVilleHeberg(?string $lbVilleHeberg): self
    {
        $this->lbVilleHeberg = $lbVilleHeberg;
        return $this;

    }

    /**
     * @return TgPersonne|null
     */
    public function getIdPersonne(): ?TgPersonne
    {
        return $this->idPersonne;
    }

    /**
     * @param TgPersonne|null $idPersonne

     */
    public function setIdPersonne(?TgPersonne $idPersonne): self
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    public function __toString()
    {
      return $this->getLbNomFr(). ' ' . $this->getLbPrenom();
    }

    public function getLbOrganisme(): ?string
    {
        return $this->lbOrganisme;
    }

    public function setLbOrganisme(?string $lbOrganisme): self
    {
        $this->lbOrganisme = $lbOrganisme;

        return $this;
    }

    public function getIdGenre(): ?TrGenre
    {
        return $this->idGenre;
    }

    public function setIdGenre(?TrGenre $idGenre): self
    {
        $this->idGenre = $idGenre;

        return $this;
    }


}
