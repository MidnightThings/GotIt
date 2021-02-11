<?php

namespace App\Entity;

use App\Repository\SessionMemberFrageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=SessionMemberFrageRepository::class)
 */
class SessionMemberFrage
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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="integer")
     */
    private $ratingcount;

    /**
     * @ORM\ManyToOne(targetEntity=SessionMember::class, inversedBy="sessionMemberFrages")
     */
    private $sessionmember;

    /**
     * @ORM\ManyToOne(targetEntity=Frage::class, inversedBy="sessionMemberFrages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $frage;

    /**
     * @ORM\ManyToMany(targetEntity=SessionMember::class, mappedBy="tmpRateAnswer")
     */
    private $sessionMembersAnswer;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
        $this->sessionMembersAnswer = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRatingcount(): ?int
    {
        return $this->ratingcount;
    }

    public function setRatingcount(int $ratingcount): self
    {
        $this->ratingcount = $ratingcount;

        return $this;
    }

    public function getSessionmember(): ?SessionMember
    {
        return $this->sessionmember;
    }

    public function setSessionmember(?SessionMember $sessionmember): self
    {
        $this->sessionmember = $sessionmember;

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

    /**
     * @return Collection|SessionMember[]
     */
    public function getSessionMembersAnswer(): Collection
    {
        return $this->sessionMembersAnswer;
    }

    public function addSessionMembersAnswer(SessionMember $sessionMembersAnswer): self
    {
        if (!$this->sessionMembersAnswer->contains($sessionMembersAnswer)) {
            $this->sessionMembersAnswer[] = $sessionMembersAnswer;
            $sessionMembersAnswer->addTmpRateAnswer($this);
        }

        return $this;
    }

    public function removeSessionMembersAnswer(SessionMember $sessionMembersAnswer): self
    {
        if ($this->sessionMembersAnswer->removeElement($sessionMembersAnswer)) {
            $sessionMembersAnswer->removeTmpRateAnswer($this);
        }

        return $this;
    }
}
