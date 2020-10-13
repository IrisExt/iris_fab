<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrGenre
 *
 * @ORM\Table(name="tr_genre", uniqueConstraints={@ORM\UniqueConstraint(name="tr_genre_pk", columns={"id_genre"})})
 * @ORM\Entity
 */
class TrGenre
{

    /**
     * @var int
     *
     * @ORM\Column(name="id_genre", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_genre_id_genre_seq", allocationSize=1, initialValue=1)
     */
    private $idGenre;

    /**
     * @var string
     *
     * @ORM\Column(name="cd_genre", type="string", length=3, nullable=false)
     */
    private $cdGenre;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_long", type="string", length=50, nullable=false, options={"comment"="valeur littérales: homme, femme, ...."})
     */
    private $lbLong;

    /**
     * @return string
     */
    public function getCdGenre(): string
    {
        return $this->cdGenre;
    }

    /**
     * @param string $cdGenre
     */
    public function setCdGenre(string $cdGenre): void
    {
        $this->cdGenre = $cdGenre;
    }

    /**
     * @return string
     */
    public function getLbLong(): string
    {
        return $this->lbLong;
    }

    /**
     * @param string $lbLong
     */
    public function setLbLong(string $lbLong): void
    {
        $this->lbLong = $lbLong;
    }

    /**
     * @return mixed
     */
    public function getIdGenre()
    {
        return $this->idGenre;
    }

    /**
     * @param mixed $idGenre
     */
    public function setIdGenre($idGenre): void
    {
        $this->idGenre = $idGenre;
    }

    public function __toString()
    {
        return $this->getLbLong();
    }


}
