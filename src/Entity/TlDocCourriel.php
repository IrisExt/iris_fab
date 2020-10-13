<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="tl_doc_courriel")
 */


class TlDocCourriel
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgCourriel", inversedBy="docCourriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_courriel", referencedColumnName="id_courriel")
     * })
     */
    private $idCourriel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\TgDocument", inversedBy="docCourriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_doc", referencedColumnName="id_doc")
     * })
     */

    private $idDoc;

    /**
     * @return mixed
     */
    public function getIdCourriel()
    {
        return $this->idCourriel;
    }

    /**
     * @param mixed $idCourriel
     */
    public function setIdCourriel($idCourriel): void
    {
        $this->idCourriel = $idCourriel;
    }

    /**
     * @return mixed
     */
    public function getIdDoc()
    {
        return $this->idDoc;
    }

    /**
     * @param mixed $idDoc
     */
    public function setIdDoc($idDoc): void
    {
        $this->idDoc = $idDoc;
    }

}