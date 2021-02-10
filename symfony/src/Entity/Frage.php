<?php

namespace App\Entity;

use App\Repository\FrageRepository;
use Doctrine\ORM\Mapping as ORM;
use Datetime;

/**
 * @ORM\Entity(repositoryClass=FrageRepository::class)
 */
class Frage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $crdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tstamp;

    /**
     * @ORM\ManyToOne(targetEntity=Kurs::class, inversedBy="frages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrdate(): ?\DateTimeInterface
    {
        return $this->crdate;
    }

    public function setCrdate(\DateTimeInterface $crdate): self
    {
        $this->crdate = $crdate;

        return $this;
    }

    public function getTstamp(): ?\DateTimeInterface
    {
        return $this->tstamp;
    }

    public function setTstamp(\DateTimeInterface $tstamp): self
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function getKurs(): ?Kurs
    {
        return $this->kurs;
    }

    public function setKurs(?Kurs $kurs): self
    {
        $this->kurs = $kurs;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
