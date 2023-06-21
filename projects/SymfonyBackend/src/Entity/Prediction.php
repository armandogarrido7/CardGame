<?php

namespace App\Entity;

use App\Repository\PredictionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PredictionRepository::class)]
class Prediction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable:true)]
    private ?int $rounds_won = null;

    #[ORM\Column(nullable: true)]
    private ?bool $will_win = null;

    #[ORM\ManyToOne(inversedBy: 'predictions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'predictions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Round $round = null;

    public function __construct(Player $player, Round $round)
    {
        $this->round = $round;
        $this->player = $player;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoundsWon(): ?int
    {
        return $this->rounds_won;
    }

    public function setRoundsWon(int $rounds_won): self
    {
        $this->rounds_won = $rounds_won;

        return $this;
    }

    public function isWillWin(): ?bool
    {
        return $this->will_win;
    }

    public function setWillWin(?bool $will_win): self
    {
        $this->will_win = $will_win;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

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
}
