<?php

/**
 * Echouage entity, created automatically by symfony CRUD and left unchanged
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

 namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Echouage
 *
 * @ORM\Table(name="echouage", indexes={@ORM\Index(name="fk_espace_id", columns={"espece_id"}), @ORM\Index(name="fk_zone_id", columns={"zone_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\EchouageRepository")
 */
class Echouage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="date", type="integer", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer", nullable=false)
     */
    private $nombre;

    /**
     * @var \Zone
     *
     * @ORM\ManyToOne(targetEntity="Zone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="zone_id", referencedColumnName="id")
     * })
     */
    private $zone;

    /**
     * @var \Espece
     *
     * @ORM\ManyToOne(targetEntity="Espece")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="espece_id", referencedColumnName="id")
     * })
     */
    private $espece;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getEspece(): ?Espece
    {
        return $this->espece;
    }

    public function setEspece(?Espece $espece): self
    {
        $this->espece = $espece;

        return $this;
    }


}
