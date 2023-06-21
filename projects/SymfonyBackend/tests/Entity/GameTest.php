<?php
namespace App\Entity;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
class GameTest extends TestCase
{
    public function testCanSetAndGetData(){
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $game->addPlayer($player);
        self::assertSame('GameTest', $game->getName());
        self::assertSame(Game::PREDICTION_TURN,$game->getTurnType());
        self::assertInstanceOf(ArrayCollection::class, $game->getPlayers());
        self::assertInstanceOf(ArrayCollection::class, $game->getRounds());
        self::assertSame($player, $game->getPlayers()[0]);
        self::assertSame(1, $game->getPlayersNumber());
        $game->removePlayer($player);
        self::assertSame(0, $game->getPlayersNumber());
        $game->setWinner(1);
        self::assertSame(1, $game->getWinner());
        self::assertSame([0,1,2,3,4],$game->getAllowedPredictions());
        self::assertSame(0,$game->getPlayersNumber());
        self::assertSame(1, $game->getCurrentRoundNumber());
        self::assertSame(5, $game->getCurrentCardsNumber());
        self::assertSame(1, $game->getCreatedBy());
    }
}
