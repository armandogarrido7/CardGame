<?php

namespace App\Transformer;

use App\Entity\Prediction;

class PredictionTransformer extends BaseTransformer
{
    public function __construct(string $mode)
    {
        parent::__construct($mode);
    }

    public function basicTransform($prediction): array
    {
        return [
            'id' => $prediction->getId(),
            'round_id' => $prediction->getRound()->getId(),
            'rounds_won' => $prediction->getRoundsWon(),
            'will_win' => $prediction->isWillWin()
        ];
    }

    public function fullTransform($prediction): array
    {
        return $this->basicTransform($prediction);
    }
}
