<?php
namespace App\Entity;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
class PlayerTest extends TestCase {
    public function testCanSetAndGetData(){
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $round = new Round($game, 1, 5);

        $player->setLives(1);

        self::assertInstanceOf(Account::class, $player->getAccount());
        self::assertInstanceOf(Game::class, $player->getGame());
        self::assertSame(1, $player->getLives());
    }
}
