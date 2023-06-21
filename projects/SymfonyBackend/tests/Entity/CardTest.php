<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Card;

class CardTest extends TestCase
{
    public function testCanSetAndGetData()
    {
        $card = new Card(2, 'Clubs');

        self::assertSame(2, $card->getNumber());
        self::assertSame('Clubs', $card->getSuit());
    }
}
