<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_cv_publ")
 *
 * */
class TlCvPubl
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgCv", inversedBy="tlCvPubl", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cv", referencedColumnName="id_cv")
     * })
     */
    private $idCv;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="TgPublication", inversedBy="tlCvPubl", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_publication", referencedColumnName="id_publication")
     * })
     */
    private $idPublication;

    /**
     * @var int
     * @ORM\Column(name="ordre", type="integer", length=2, nullable=false)
     */
    private $ordre;

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getIdCv(): ?TgCv
    {
        return $this->idCv;
    }

    public function setIdCv(?TgCv $idCv): self
    {
        $this->idCv = $idCv;

        return $this;
    }

    public function getIdPublication(): ?TgPublication
    {
        return $this->idPublication;
    }

    public function setIdPublication(?TgPublication $idPublication): self
    {
        $this->idPublication = $idPublication;

        return $this;
    }

}