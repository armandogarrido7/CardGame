<?php
namespace App\Transformer;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class PlayerTransformer extends BaseTransformer
{
    private Manager $manager;
    private AccountTransformer $accountTransformer;
    private PredictionTransformer $predictionTransformer;
    private PlayTransformer $playTransformer;
    private CardTransformer $cardTransformer;
    public function __construct(string $mode)
    {
        parent::__construct($mode);
        $this->manager = new Manager();
        $this->accountTransformer = new AccountTransformer(BaseTransformer::BASIC);
        $this->predictionTransformer = new PredictionTransformer(BaseTransformer::BASIC);
        $this->playTransformer = new PlayTransformer(BaseTransformer::BASIC);
        $this->cardTransformer = new CardTransformer(BaseTransformer::BASIC);

    }
    public function basicTransform($player): array
    {
        return [
            'player_id' => $player->getId(),
            'account' => $this->manager->createData(new Item($player->getAccount(), $this->accountTransformer)),
            'game_id' => $player->getGame()->getId(),
            'deck' => $this->manager->createData(new Collection($player->getDeck(), $this->cardTransformer)),
            'lives' => $player->getLives(),
            'rounds_won' => $player->getRoundsWon(),
            'predictions' => $this->manager->createData(new Collection($player->getPredictions(), $this->predictionTransformer)),
            'plays' => $this->manager->createData(new Collection($player->getPlays(), $this->playTransformer))
        ];
    }

    public function fullTransform($player): array
    {
        return $this->basicTransform($player);
    }
}
