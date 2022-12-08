<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_categorie", columns={"id_categorie"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_rec", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRec;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname_rec_s", type="string", length=255, nullable=false)
     */
    private $fullnameRecS;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname_rec_r", type="string", length=255, nullable=false)
     */
    private $fullnameRecR;

    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     */
    private $idCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=255, nullable=false)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="text_rec", type="string", length=1500, nullable=false)
     */
    private $textRec;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $status = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_de_rec", type="date", nullable=false)
     */
    private $dateDeRec;


}
