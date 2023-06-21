<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayRepository::class)]
class Play
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $card_flipped = true;

    #[ORM\ManyToOne(inversedBy: 'plays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Card $card = null;

    #[ORM\ManyToOne(inversedBy: 'plays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subround $subround = null;

    public function __construct(Player $player, Subround $subround, Card $card)
    {
        $this->player = $player;
        $this->subround = $subround;
        $this->card = $card;
    }

    public function setId(int $id): Play
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCardFlipped(): ?bool
    {
        return $this->card_flipped;
    }

    public function setCardFlipped(bool $card_flipped): self
    {
        $this->card_flipped = $card_flipped;

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

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getSubround(): ?Subround
    {
        return $this->subround;
    }

    public function setSubround(?Subround $subround): self
    {
        $this->subround = $subround;

        return $this;
    }
}
