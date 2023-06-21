<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    public function testCanSetAndGetData()
    {
        $card = new Card('1', 'Clubs');
        $account = new Account('email@email.com', 'AccountTest', 'account_test_profile_pic');
        $gameStatus = new GameStatus();
        $game = new Game('GameTest', 2, 1, $gameStatus);
        $player = new Player($account, $game);
        $round = new Round($game, 1,5);
        $prediction = new Prediction($player, $round);
        $subround = new Subround($round, 5);

        $round->addSubround($subround);
        $round->setFinished(true);
        $round->setCardsNumber(5);

        self::assertSame($subround, $round->getSubrounds()[0]);
        self::assertSame(5, $round->getCardsNumber());
        self::assertSame(true, $round->isFinished());
    }
}
