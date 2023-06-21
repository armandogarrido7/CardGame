<?php

namespace App\Transformer;

use App\Entity\Round;

class RoundTransformer extends BaseTransformer
{
    public function __construct(string $mode)
    {
        parent::__construct($mode);
    }

    public function basicTransform($round): array
    {
        return [
            'round_id' => $round->getId(),
            'player_id' => $round->getPlayer()->getId(),
            'round_number' => $round->getNumber()
        ];
    }

    public function fullTransform($round): array
    {
        $transform = $this->basicTransform($round);
        return array_merge($transform, [
            'finished' => $round->isFinishedRound(),
            'cards_number' => $round->getCardsNumber(),
            'lives_lost' => $round->getLivesLost(),
            'won' => $round->isRoundWon()
        ]);
    }
}
