<?php

namespace App\Entity;

use App\Repository\SessionMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=SessionMemberRepository::class)
 */
class SessionMember
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
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="sessionMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\OneToOne(targetEntity=SessionMemberFrage::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $sessionMemberFrage;

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

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getSessionmemberFrage(): ?SessionMemberFrage
    {
        return $this->sessionMemberFrage;
    }

    public function setSessionmemberFrage(SessionMemberFrage $sessionMemberFrage): self
    {
        $this->sessionMemberFrage = $sessionMemberFrage;

        return $this;
    }
}
