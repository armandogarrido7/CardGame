<?php
namespace App\Transformer;
use App\Entity\Account;
use App\Repository\AccountRepository;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class AccountTransformer extends BaseTransformer
{
    private Manager $manager;
    private PlayerTransformer $playerTransformer;
    public function __construct(string $mode)
    {
        parent::__construct($mode);
        $this->manager = new Manager();
    }
    public function basicTransform($account): array
    {
        return [
            'account_id' => $account->getId(),
            'player_id' => $account->getPlayerId(),
            'email' => $account->getEmail(),
            'name' => $account->getName(),
            'profile_pic' => $account->getProfilePic(),
        ];
    }

    public function fullTransform($account): array
    {
        $transform = $this->basicTransform($account);
        return array_merge($transform, [
            'current_game' => $account->getCurrentGame(),
            'games_played' => $account->getGamesPlayed(),
            'wins' => $account->getWins()
        ]);
    }
}
