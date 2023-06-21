<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use App\Service\AccountService;
use App\Transformer\BaseTransformer;
use App\Transformer\AccountTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    private AccountService $accountService;
    private AccountRepository $accountRepository;
    private AccountTransformer $accountTransformer;
    private Manager $manager;

    public function __construct(AccountService    $accountService,
                                AccountRepository $accountRepository)
    {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository;
        $this->accountTransformer = new AccountTransformer(BaseTransformer::FULL_TRANSFORM);
        $this->manager = new Manager();
    }

    #[Route('/get', name: 'app_account_get', methods: ['POST'])]
    public function getAccountData(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (!key_exists('account_id', $data))
        {
            return $this->json(['errorMessage' => 'Request should contain "account_id" parameter'],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        try {
            $account = $this->accountRepository->find($data['account_id']);
            if ($account) {
                $account_data = $this->manager->createData(new Item($account, $this->accountTransformer));
                return $this->json($account_data, Response::HTTP_OK);
            }
            return $this->json(['errorMessage' => 'There is no account with ID '.$data['account_id']], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/list', name: 'app_account_list', methods: ['GET'])]
    public function index(): Response
    {
        try {
            $accounts = $this->accountRepository->findAll();
            $accounts_data = $this->manager->createData(new Collection($accounts, $this->accountTransformer));
            return $this->json($accounts_data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/new', name: 'app_account_new', methods: ['POST'])]
    public function newAccount(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (!key_exists('email', $data) ||
            !key_exists('name', $data) ||
            !key_exists('profile_pic', $data))
        {
            return $this->json(['errorMessage' => 'Request should contain "email", "username", "password" and "name" parameters'],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        try {
            $account = $this->accountService->newAccount($data['email'],
                $data['name'],
                $data['profile_pic']
            );
            $account_data = $this->manager->createData(new Item($account, $this->accountTransformer));
            return $this->json($account_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }
}
