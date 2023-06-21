<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column(nullable: true)]
    private ?int $lives = 5;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private ?bool $isWinner = false;

    #[ORM\ManyToMany(targetEntity: Card::class)]
    private Collection $deck;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Prediction::class, orphanRemoval: true)]
    private Collection $predictions;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Play::class, orphanRemoval: true)]
    #[ORM\OrderBy(["id" => "ASC"])]
    private Collection $plays;

    #[ORM\Column(nullable: true)]
    private ?int $rounds_won = 0;

    #[ORM\Column(nullable: true)]
    private ?int $turn_number = null;

    public function __construct(Account $account, Game $game)
    {
        $this->account = $account;
        $this->game = $game;
        $this->deck = new ArrayCollection();
        $this->predictions = new ArrayCollection();
        $this->plays = new ArrayCollection();
    }

    public function __toString()
    {
        return strval($this->id);
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getLives(): ?int
    {
        return $this->lives;
    }

    public function setLives(?int $lives): self
    {
        $this->lives = $lives;

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

    public function isIsWinner(): ?bool
    {
        return $this->isWinner;
    }

    public function setIsWinner(bool $isWinner): self
    {
        $this->isWinner = $isWinner;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getDeck(): Collection
    {
        return $this->deck;
    }

    public function addDeck(Card $deck): self
    {
        if (!$this->deck->contains($deck)) {
            $this->deck->add($deck);
        }

        return $this;
    }

    public function setDeck(Collection $deck): self
    {
        $this->deck = $deck;
        return $this;
    }

    public function removeDeck(Card $deck): self
    {
        $this->deck->removeElement($deck);

        return $this;
    }

    /**
     * @return Collection<int, Prediction>
     */
    public function getPredictions(): Collection
    {
        return $this->predictions;
    }

    public function addPrediction(Prediction $prediction): self
    {
        if (!$this->predictions->contains($prediction)) {
            $this->predictions->add($prediction);
            $prediction->setPlayer($this);
        }

        return $this;
    }

    public function removePrediction(Prediction $prediction): self
    {
        if ($this->predictions->removeElement($prediction)) {
            // set the owning side to null (unless already changed)
            if ($prediction->getPlayer() === $this) {
                $prediction->setPlayer(null);
            }
        }

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
            $play->setPlayer($this);
        }

        return $this;
    }

    public function removePlay(Play $play): self
    {
        if ($this->plays->removeElement($play)) {
            // set the owning side to null (unless already changed)
            if ($play->getPlayer() === $this) {
                $play->setPlayer(null);
            }
        }

        return $this;
    }

    public function getRoundsWon(): ?int
    {
        return $this->rounds_won;
    }

    public function setRoundsWon(?int $rounds_won): self
    {
        $this->rounds_won = $rounds_won;

        return $this;
    }

    public function getTurnNumber(): ?int
    {
        return $this->turn_number;
    }

    public function setTurnNumber(?int $turn_number): self
    {
        $this->turn_number = $turn_number;

        return $this;
    }
}
