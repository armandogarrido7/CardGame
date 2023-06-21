<?php
use PHPUnit\Framework\TestCase;
use App\Entity\Account;

class AccountTest extends TestCase{
    public function testCanSetAndGetData(){
        $account = new Account('armando.garrido@aircury.com', 'Armando', 'profile_pic_url',);

        self::assertSame('Armando', $account->getName());
        self::assertSame('armando.garrido@aircury.com', $account->getEmail());
        self::assertSame('profile_pic_url', $account->getProfilePic());
        self::assertSame(0, $account->getWins());
        self::assertSame(false, $account->isVerified());
        self::assertIsArray($account->getRoles());
        self::assertSame('ROLE_USER', $account->getRoles()[0]);
        self::assertSame('armando.garrido@aircury.com', $account->getUserIdentifier());
    }
}
