<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    public const PREDICTION_TURN = 'prediction';
    public const PLAY_TURN = 'play';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Player::class, orphanRemoval: true)]
    #[ORM\OrderBy(["turn_number" => "ASC"])]
    private Collection $players;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $max_players = 9;

    #[ORM\Column]
    private ?int $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameStatus $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $turn_type = Game::PREDICTION_TURN;

    #[ORM\Column]
    private ?int $current_cards_number = 5;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $allowed_predictions = [];

    #[ORM\Column(nullable: true)]
    private ?int $current_player = null;

    #[ORM\Column(nullable: true)]
    private ?int $current_round_number = 1;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $players_turns = [];

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Round::class, orphanRemoval: true)]
    private Collection $rounds;

    #[ORM\Column(nullable: true)]
    private ?int $winner = 0;

    public function __construct(string $name, int $players_num, int $account_id, GameStatus $gameStatus)
    {
        $this->name = $name;
        $this->max_players = $players_num;
        $this->createdBy = $account_id;
        $this->status = $gameStatus;
        $this->allowed_predictions = $this->calculateAllowedPredictions();
        $this->players = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

    public function setId($id): Game
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getGame() === $this) {
                $player->setGame(null);
            }
        }

        return $this;
    }

    public function getPlayersNumber(): int
    {
        return count($this->players);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxPlayers(): ?int
    {
        return $this->max_players;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getStatus(): ?GameStatus
    {
        return $this->status;
    }

    public function setStatus(?GameStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTurnType(): ?string
    {
        return $this->turn_type;
    }

    public function setTurnType(?string $turn_type): self
    {
        $this->turn_type = $turn_type;

        return $this;
    }

    public function getCurrentCardsNumber(): ?int
    {
        return $this->current_cards_number;
    }

    public function setCurrentCardsNumber(int $current_cards_number): self
    {
        $this->current_cards_number = $current_cards_number;

        return $this;
    }

    public function getAllowedPredictions(): array
    {
        return $this->allowed_predictions;
    }

    public function setAllowedPredictions(?array $allowed_predictions): self
    {
        $this->allowed_predictions = $allowed_predictions;

        return $this;
    }

    public function calculateAllowedPredictions(): array
    {
        $allowed_predictions = [];
        for ($i = 0; $i < $this->current_cards_number; $i++) {
            $allowed_predictions[] = $i;
        }
        return $allowed_predictions;
    }

    public function getCurrentPlayer(): ?int
    {
        return $this->current_player;
    }

    public function setCurrentPlayer(?int $current_player): self
    {
        $this->current_player = $current_player;

        return $this;
    }

    public function getCurrentRoundNumber(): ?int
    {
        return $this->current_round_number;
    }

    public function setCurrentRoundNumber(?int $current_round_number): self
    {
        $this->current_round_number = $current_round_number;

        return $this;
    }

    public function getPlayersTurns(): array
    {
        return $this->players_turns;
    }

    public function setPlayersTurns(?array $players_turns): self
    {
        $this->players_turns = $players_turns;

        return $this;
    }

    /**
     * @return Collection<int, Round>
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds->add($round);
            $round->setGame($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getGame() === $this) {
                $round->setGame(null);
            }
        }

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
