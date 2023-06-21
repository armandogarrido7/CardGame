<?php

namespace App\Transformer;

use App\Entity\Game;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class GameTransformer extends BaseTransformer
{
    private Manager $manager;
    private PlayerTransformer $playerTransformer;
    public function __construct(string $mode)
    {
        parent::__construct($mode);
        $this->manager = new Manager();
        $this->playerTransformer = new PlayerTransformer(BaseTransformer::BASIC);
    }

    public function basicTransform($game): array
    {
        return [
            'game_id' => $game->getId(),
            'name' => $game->getName(),
            'created_by' => $game->getCreatedBy(),
            'max_players' => $game->getMaxPlayers(),
            'players_number' => $game->getPlayersNumber(),
            'status_name' => $game->getStatus()->getName(),
            'status_description' => $game->getStatus()->getDescription(),
        ];
    }

    public function fullTransform($game): array
    {
        $transform = $this->basicTransform($game);
        return array_merge($transform, [
            'allowed_predictions' => $game->getAllowedPredictions(),
            'current_cards_number' => $game->getCurrentCardsNumber(),
            'current_player' => $game->getCurrentPlayer(),
            'current_round_number' => $game->getCurrentRoundNumber(),
            'players' => $this->manager->createData(new Collection($game->getPlayers(), $this->playerTransformer)),
            'players_turns' => $game->getPlayersTurns(),
            'turn_type' => $game->getTurnType(),
            'winner' => $game->getWinner()
        ]);
    }
}
