<?php

namespace App\Entity;

use App\Repository\KursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Datetime;

/**
 * @ORM\Entity(repositoryClass=KursRepository::class)
 */
class Kurs
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="kurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Frage::class, mappedBy="kurs")
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $frages;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="kurs")
     */
    private $sessions;

    public function __construct()
    {
        $this->crdate = new Datetime();
        $this->tstamp = new Datetime();
        $this->frages = new ArrayCollection();
        $this->sessions = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|Frage[]
     */
    public function getFrages(): Collection
    {
        return $this->frages;
    }

    public function addFrage(Frage $frage): self
    {
        if (!$this->frages->contains($frage)) {
            $this->frages[] = $frage;
            $frage->setKurs($this);
        }

        return $this;
    }

    public function removeFrage(Frage $frage): self
    {
        if ($this->frages->removeElement($frage)) {
            // set the owning side to null (unless already changed)
            if ($frage->getKurs() === $this) {
                $frage->setKurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setKurs($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getKurs() === $this) {
                $session->setKurs(null);
            }
        }

        return $this;
    }
}
