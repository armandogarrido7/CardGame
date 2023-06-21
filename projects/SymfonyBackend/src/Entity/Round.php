<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoundRepository::class)]
class Round
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cards_number = 5;

    #[ORM\Column]
    private ?bool $finished = false;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'rounds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\OneToMany(mappedBy: 'round', targetEntity: Subround::class, orphanRemoval: true)]
    private Collection $subrounds;


    public function __construct(Game $game, int $number, int $cards_number)
    {
        $this->game = $game;
        $this->number = $number;
        $this->cards_number = $cards_number;
        $this->subrounds = new ArrayCollection();
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
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

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished= $finished;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, Subround>
     */
    public function getSubrounds(): Collection
    {
        return $this->subrounds;
    }

    public function addSubround(Subround $subround): self
    {
        if (!$this->subrounds->contains($subround)) {
            $this->subrounds->add($subround);
            $subround->setRound($this);
        }

        return $this;
    }

    public function removeSubround(Subround $subround): self
    {
        if ($this->subrounds->removeElement($subround)) {
            // set the owning side to null (unless already changed)
            if ($subround->getRound() === $this) {
                $subround->setRound(null);
            }
        }

        return $this;
    }


}
