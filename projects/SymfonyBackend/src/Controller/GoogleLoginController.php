<?php

namespace App\Controller;

use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JsonException;
use Exception;

class GoogleLoginController extends AbstractController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    #[Route('/login/google', name: 'app_google_login', methods: ['POST'])]
    public function index(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage(), 'data' => $request->getContent()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('token', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "token" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $token = $this->accountService->googleLogin($data['token']);
            return $this->json(['token' => $token], Response::HTTP_OK);
        } catch (\Google\Exception $google_exception) {
            return $this->json(['errorMessage' => $google_exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json(['errorMessage' => 'Invalid Token', 'exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/token/check', methods: ['POST'])]
    public function checkToken(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage(), 'data' => $request->getContent()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('token', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "token" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $isTokenValid = $this->accountService->checkToken($data['token']);
            return $this->json(['is_valid_token' => $isTokenValid], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['errorMessage' => 'Invalid Token', 'exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/token/renew', methods: ['POST'])]
    public function renewToken(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage(), 'data' => $request->getContent()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('token', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "token" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $token = $this->accountService->renewToken($data['token']);
            return $this->json(['token' => $token], Response::HTTP_OK);
        } catch (\Google\Exception $google_exception) {
            return $this->json(['errorMessage' => $google_exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json(['errorMessage' => 'Invalid Token', 'exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
