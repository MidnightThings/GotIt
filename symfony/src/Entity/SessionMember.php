<?php

namespace App\Entity;

use App\Repository\SessionMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=SessionMemberFrage::class, mappedBy="sessionmember")
     */
    private $sessionMemberFrages;

    /**
     * @ORM\ManyToMany(targetEntity=SessionMemberFrage::class, inversedBy="sessionMembersAnswer")
     */
    private $tmpRateAnswer;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
        $this->sessionMemberFrages = new ArrayCollection();
        $this->tmpRateAnswer = new ArrayCollection();
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

    /**
     * @return Collection|SessionMemberFrage[]
     */
    public function getSessionMemberFrages(): Collection
    {
        return $this->sessionMemberFrages;
    }

    public function addSessionMemberFrage(SessionMemberFrage $sessionMemberFrage): self
    {
        if (!$this->sessionMemberFrages->contains($sessionMemberFrage)) {
            $this->sessionMemberFrages[] = $sessionMemberFrage;
            $sessionMemberFrage->setSessionmember($this);
        }

        return $this;
    }

    public function removeSessionMemberFrage(SessionMemberFrage $sessionMemberFrage): self
    {
        if ($this->sessionMemberFrages->removeElement($sessionMemberFrage)) {
            // set the owning side to null (unless already changed)
            if ($sessionMemberFrage->getSessionmember() === $this) {
                $sessionMemberFrage->setSessionmember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SessionMemberFrage[]
     */
    public function getTmpRateAnswer(): Collection
    {
        return $this->tmpRateAnswer;
    }

    public function addTmpRateAnswer(SessionMemberFrage $tmpRateAnswer): self
    {
        if (!$this->tmpRateAnswer->contains($tmpRateAnswer)) {
            $this->tmpRateAnswer[] = $tmpRateAnswer;
        }

        return $this;
    }

    public function removeTmpRateAnswer(SessionMemberFrage $tmpRateAnswer): self
    {
        $this->tmpRateAnswer->removeElement($tmpRateAnswer);

        return $this;
    }
}
