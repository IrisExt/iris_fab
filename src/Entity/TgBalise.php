<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TgBalise
 *
 * @ORM\Table(name="tg_balise", uniqueConstraints={@ORM\UniqueConstraint(name="tg_balise_pk", columns={"id_balise"})}, indexes={@ORM\Index(name="balise_de_categorie_fk", columns={"id_cat_balise"})})
 * @ORM\Entity
 */
class TgBalise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_balise", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tg_balise_id_balise_seq", allocationSize=1, initialValue=1)
     */
    private $idBalise;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=50, nullable=false)
     */
    private $lbNom;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=false)
     */
    private $lbNomEn;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_format", type="string", length=255, nullable=false, options={"comment"="Pr�cise si le mod�le ou le courriel est au format 'html' ou texte..."})
     */
    private $lbFormat;

    /**
     * @var \TrCatBalise
     *
     * @ORM\ManyToOne(targetEntity="TrCatBalise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_balise", referencedColumnName="id_cat_balise")
     * })
     */
    private $idCatBalise;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TgCourriel", mappedBy="idBalise")
     */
    private $idCourriel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCourriel = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
