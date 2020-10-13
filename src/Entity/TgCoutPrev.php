<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgCoutPrev
 *
 * @ORM\Table(name="tg_cout_prev", uniqueConstraints={@ORM\UniqueConstraint(name="tg_cout_prev_pk", columns={"id_cout_prv"})}, indexes={@ORM\Index(name="a_pour_cout2_fk", columns={"id_partenaire"})})
 * @ORM\Entity
 */
class TgCoutPrev
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cout_prv", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_cout_prev_id_cout_prv_seq", allocationSize=1, initialValue=1)
     */
    private $idCoutPrv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_perm", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersPerm;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_perm_mois", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersPermMois;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_np_nf", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersNpNf;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_np_nf_mois", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersNpNfMois;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_np", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersNp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_pers_np_mois", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPersNpMois;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_dech_ens", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntDechEns;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_dech_ens_mois", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntDechEnsMois;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_inst_mat", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntInstMat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_bat_ter", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntBatTer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_prest", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntPrest;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mnt_frais_g", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $mntFraisG;

    /**
     * @var \TgPartenariat
     *
     * @ORM\ManyToOne(targetEntity="TgPartenariat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_partenaire", referencedColumnName="id_partenaire")
     * })
     */
    private $idPartenaire;

    public function getIdCoutPrv(): ?int
    {
        return $this->idCoutPrv;
    }

    public function getMntPersPerm()
    {
        return $this->mntPersPerm;
    }

    public function setMntPersPerm($mntPersPerm): self
    {
        $this->mntPersPerm = $mntPersPerm;

        return $this;
    }

    public function getMntPersPermMois()
    {
        return $this->mntPersPermMois;
    }

    public function setMntPersPermMois($mntPersPermMois): self
    {
        $this->mntPersPermMois = $mntPersPermMois;

        return $this;
    }

    public function getMntPersNpNf()
    {
        return $this->mntPersNpNf;
    }

    public function setMntPersNpNf($mntPersNpNf): self
    {
        $this->mntPersNpNf = $mntPersNpNf;

        return $this;
    }

    public function getMntPersNpNfMois()
    {
        return $this->mntPersNpNfMois;
    }

    public function setMntPersNpNfMois($mntPersNpNfMois): self
    {
        $this->mntPersNpNfMois = $mntPersNpNfMois;

        return $this;
    }

    public function getMntPersNp()
    {
        return $this->mntPersNp;
    }

    public function setMntPersNp($mntPersNp): self
    {
        $this->mntPersNp = $mntPersNp;

        return $this;
    }

    public function getMntPersNpMois()
    {
        return $this->mntPersNpMois;
    }

    public function setMntPersNpMois($mntPersNpMois): self
    {
        $this->mntPersNpMois = $mntPersNpMois;

        return $this;
    }

    public function getMntDechEns()
    {
        return $this->mntDechEns;
    }

    public function setMntDechEns($mntDechEns): self
    {
        $this->mntDechEns = $mntDechEns;

        return $this;
    }

    public function getMntDechEnsMois()
    {
        return $this->mntDechEnsMois;
    }

    public function setMntDechEnsMois($mntDechEnsMois): self
    {
        $this->mntDechEnsMois = $mntDechEnsMois;

        return $this;
    }

    public function getMntInstMat()
    {
        return $this->mntInstMat;
    }

    public function setMntInstMat($mntInstMat): self
    {
        $this->mntInstMat = $mntInstMat;

        return $this;
    }

    public function getMntBatTer()
    {
        return $this->mntBatTer;
    }

    public function setMntBatTer($mntBatTer): self
    {
        $this->mntBatTer = $mntBatTer;

        return $this;
    }

    public function getMntPrest()
    {
        return $this->mntPrest;
    }

    public function setMntPrest($mntPrest): self
    {
        $this->mntPrest = $mntPrest;

        return $this;
    }

    public function getMntFraisG()
    {
        return $this->mntFraisG;
    }

    public function setMntFraisG($mntFraisG): self
    {
        $this->mntFraisG = $mntFraisG;

        return $this;
    }

    public function getIdPartenaire(): ?TgPartenariat
    {
        return $this->idPartenaire;
    }

    public function setIdPartenaire(?TgPartenariat $idPartenaire): self
    {
        $this->idPartenaire = $idPartenaire;

        return $this;
    }


}
