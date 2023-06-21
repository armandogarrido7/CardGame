<?php
namespace App\Entity;
use PHPUnit\Framework\TestCase;
class PlayTest extends TestCase{
    public function testCanSetAndGetData(){
        $card = new Card('1', 'Clubs');
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $round = new Round($game, 1,5);
        $subround = new Subround($round, 5);
        $play = new Play($player, $subround, $card);

        self::assertSame($card, $play->getcard());
        self::assertSame($subround, $play->getSubRound());
    }
}
