<?php

namespace App\Entity;

use App\Repository\FrageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=SessionMemberFrage::class, mappedBy="frage")
     */
    private $sessionMemberFrages;

    /**
     * @ORM\Column(type="smallint")
     */
    private $sortorder;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
        $this->sessionMemberFrages = new ArrayCollection();
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
        if($this->sortorder === 0) {
            $allQuestions = $kurs->getFrages();
            $highestOrder = -1;
            if(count($allQuestions) > 0) {
                foreach($allQuestions as $question) {
                    $order = $question->getSortorder();
                    if($highestOrder < $order) $highestOrder = $order;
                }
            }
            $this->sortorder = $highestOrder + 1;
        }

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
            $sessionMemberFrage->setFrage($this);
        }

        return $this;
    }

    public function removeSessionMemberFrage(SessionMemberFrage $sessionMemberFrage): self
    {
        if ($this->sessionMemberFrages->removeElement($sessionMemberFrage)) {
            // set the owning side to null (unless already changed)
            if ($sessionMemberFrage->getFrage() === $this) {
                $sessionMemberFrage->setFrage(null);
            }
        }

        return $this;
    }

    public function getSortorder(): ?int
    {
        return $this->sortorder;
    }

    public function setSortorder(int $sortorder): self
    {
        $this->sortorder = $sortorder;

        return $this;
    }
}
