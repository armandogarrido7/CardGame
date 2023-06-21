<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Transformer\AccountTransformer;
use App\Transformer\BaseTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Util\Exception;

class AccountService
{
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private AccountTransformer $accountTransformer;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(EntityManagerInterface   $entityManager,
                                AccountRepository        $accountRepository,
                                JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
        $this->accountTransformer = new AccountTransformer(BaseTransformer::FULL_TRANSFORM);
        $this->jwtManager = $jwtManager;
    }

    public function newAccount(string $email,
                               string $name,
                               string $profile_pic
    ): Account
    {
        $account1 = $this->accountRepository->findOneBy(["email" => $email]);
        if ($account1) {
            throw new Exception("Can't be 2 accounts with the same email");
        }
        return $this->accountRepository->newAccount($email, $name, $profile_pic);
    }

    public function googleLogin(string $token): string
    {
        $google_client = new \Google_Client(['client_id' => getenv('GOOGLE_CLIENT_ID')]);
        $payload = $google_client->verifyIdToken($token);
        if (!$payload) {
            throw new \Google\Exception("Invalid Google Token");
        }
        $email = $payload['email'];
        $name = $payload['name'];
        $profile_pic = $payload['picture'];
        $account = $this->accountRepository->findOneBy(['email' => strval($email)]);
        if (!$account) {
            $account = $this->accountRepository->newAccount($email, $name, $profile_pic);
        }
        $account_payload = $this->accountTransformer->fullTransform($account);
        return $this->jwtManager->createFromPayload($account, $account_payload);
    }

    public function checkToken(string $token): bool
    {
        try {
            $payload = $this->jwtManager->parse($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function renewToken(string $token): ?string
    {
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $email = $jwtPayload->email;
        $account = $this->accountRepository->findOneBy(['email' => strval($email)]);
        $account_payload = $this->accountTransformer->fullTransform($account);
        return $this->jwtManager->createFromPayload($account, $account_payload);
    }
}
