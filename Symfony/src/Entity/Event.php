<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Idk\LegoBundle\Annotation\Entity as Lego;
/**
 * @Lego\Entity(config="App\Configurator\EventConfigurator")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Lego\EntityForm(fields={"libelle","start","end","level","spaceStart","spaceEnd","resume","picture"})
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Lego\Field()
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @Lego\Field()
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @Lego\Field()
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @Lego\Field(path="show")
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @Lego\Field()
     * @ORM\Column(type="string", length=50)
     */
    private $spaceStart;

    /**
     * @Lego\Field()
     * @ORM\Column(type="string", length=50)
     */
    private $spaceEnd;

    /**
     * @Lego\Field()
     * @ORM\Column(type="text")
     */
    private $resume;

    /**
     * @var int
     *
     * @Lego\File(directory="public/uploads/event")
     * @Lego\Form\FileForm()
     * @Lego\Field(label="Image", image={"directory":"/uploads/event","width":"100px"})
     * @ORM\Column(name="picture", type="string")
     */
    private $picture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSpaceStart(): ?string
    {
        return $this->spaceStart;
    }

    public function setSpaceStart(string $spaceStart): self
    {
        $this->spaceStart = $spaceStart;

        return $this;
    }

    public function getSpaceEnd(): ?string
    {
        return $this->spaceEnd;
    }

    public function setSpaceEnd(string $spaceEnd): self
    {
        $this->spaceEnd = $spaceEnd;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }




}
