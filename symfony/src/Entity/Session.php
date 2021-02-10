<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
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
     * @ORM\ManyToOne(targetEntity=Kurs::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=SessionMember::class, mappedBy="session")
     */
    private $sessionMembers;

    /**
     * @ORM\OneToOne(targetEntity=Frage::class, cascade={"persist", "remove"})
     */
    private $frage;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
        $this->sessionMembers = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|SessionMember[]
     */
    public function getSessionMembers(): Collection
    {
        return $this->sessionMembers;
    }

    public function addSessionMember(SessionMember $sessionMember): self
    {
        if (!$this->sessionMembers->contains($sessionMember)) {
            $this->sessionMembers[] = $sessionMember;
            $sessionMember->setSession($this);
        }

        return $this;
    }

    public function removeSessionMember(SessionMember $sessionMember): self
    {
        if ($this->sessionMembers->removeElement($sessionMember)) {
            // set the owning side to null (unless already changed)
            if ($sessionMember->getSession() === $this) {
                $sessionMember->setSession(null);
            }
        }

        return $this;
    }

    public function getFrage(): ?Frage
    {
        return $this->frage;
    }

    public function setFrage(?Frage $frage): self
    {
        $this->frage = $frage;

        return $this;
    }
}
