<?php

namespace App\Entity;
use PHPUnit\Framework\TestCase;

class PredictionTest extends TestCase
{
    public function testCanSetAndGetData()
    {
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $round = new Round($game, 1,5);
        $prediction = new Prediction($player, $round);

        $prediction->setRoundsWon(2);
        $prediction->setWillWin(true);

        self::assertSame($round, $prediction->getRound());
        self::assertSame(2, $prediction->getRoundsWon());
        self::assertSame(true, $prediction->isWillWin());
    }
}
