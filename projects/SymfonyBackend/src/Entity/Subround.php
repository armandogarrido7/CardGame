<?php

namespace App\Entity;

use App\Repository\SubroundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubroundRepository::class)]
class Subround
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cards_number = null;

    #[ORM\OneToMany(mappedBy: 'subround', targetEntity: Play::class, orphanRemoval: true)]
    private Collection $plays;

    #[ORM\ManyToOne(inversedBy: 'subrounds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Round $round = null;

    #[ORM\Column(nullable: true)]
    private ?int $winner = null;

    public function __construct(Round $round, int $cards_number)
    {
        $this->round = $round;
        $this->cards_number = $cards_number;
        $this->plays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardsNumber(): ?int
    {
        return $this->cards_number;
    }

    public function setCardsNumber(int $cards_number): self
    {
        $this->cards_number = $cards_number;

        return $this;
    }

    /**
     * @return Collection<int, Play>
     */
    public function getPlays(): Collection
    {
        return $this->plays;
    }

    public function addPlay(Play $play): self
    {
        if (!$this->plays->contains($play)) {
            $this->plays->add($play);
            $play->setSubround($this);
        }

        return $this;
    }

    public function removePlay(Play $play): self
    {
        if ($this->plays->removeElement($play)) {
            // set the owning side to null (unless already changed)
            if ($play->getSubround() === $this) {
                $play->setSubround(null);
            }
        }

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getWinner(): ?int
    {
        return $this->winner;
    }

    public function setWinner(?int $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
