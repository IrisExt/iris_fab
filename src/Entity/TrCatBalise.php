<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrCatBalise
 *
 * @ORM\Table(name="tr_cat_balise", uniqueConstraints={@ORM\UniqueConstraint(name="tr_cat_balise_pk", columns={"id_cat_balise"})})
 * @ORM\Entity
 */
class TrCatBalise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_balise", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tr_cat_balise_id_cat_balise_seq", allocationSize=1, initialValue=1)
     */
    private $idCatBalise;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_fr", type="string", length=50, nullable=false)
     */
    private $lbNomFr;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom_en", type="string", length=50, nullable=false)
     */
    private $lbNomEn;


}
