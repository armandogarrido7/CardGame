<?php

namespace App\Transformer;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class PlayTransformer extends BaseTransformer
{
    private Manager $manager;
    private CardTransformer $cardTransformer;

    public function __construct(string $mode)
    {
        parent::__construct($mode);
        $this->manager = new Manager();
        $this->cardTransformer = new CardTransformer(BaseTransformer::BASIC);
    }

    public function basicTransform($play): array
    {
        return [
            'play_id' => $play->getId(),
            'card' => $this->manager->createData(new Item($play->getCard(), $this->cardTransformer)),
            'card_flipped' => $play->isCardFlipped()
        ];
    }

    public function fullTransform($play): array
    {
        return $this->basicTransform($play);
    }
}
