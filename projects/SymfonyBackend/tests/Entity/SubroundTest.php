<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SubroundTest extends TestCase
{
    public function testCanSetAndGetData()
    {
        $card = new Card('1', 'Clubs');
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $round = new Round($game, 1, 5);
        $subround = new Subround($round, 5);
        $play = new Play($player, $subround, $card);

        $subround->addPlay($play);
        $subround->setWinner(1);

        self::assertSame($play, $subround->getPlays()[0]);
        self::assertSame(1, $subround->getWinner());
        self::assertSame($round, $subround->getRound());
        self::assertSame(5, $subround->getCardsNumber());
    }
}
