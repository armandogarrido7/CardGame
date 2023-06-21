<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use App\Service\GameService;
use App\Service\MercureService;
use App\Transformer\BaseTransformer;
use App\Transformer\GameTransformer;
use App\Transformer\PlayerTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/player')]
class PlayerController extends AbstractController
{
    private PlayerRepository $playerRepository;
    private PlayerTransformer $playerTransformer;
    private GameTransformer $gameTransformer;
    private GameService $gameService;
    private MercureService $mercureService;
    private Manager $manager;

    public function __construct(PlayerRepository $playerRepository, GameService $gameService, MercureService $mercureService)
    {
        $this->playerRepository = $playerRepository;
        $this->gameService = $gameService;
        $this->mercureService = $mercureService;
        $this->playerTransformer = new PlayerTransformer(BaseTransformer::BASIC);
        $this->gameTransformer = new GameTransformer(BaseTransformer::FULL_TRANSFORM);
        $this->manager = new Manager();
    }

    #[Route('/new', name: 'app_player_new', methods: ['POST'])]
    public function addPlayerToGame(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('game_id', $data) || !key_exists('account_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id" and "account_id" parameters'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->gameTransformer->setMode(BaseTransformer::FULL_TRANSFORM);
            $player = $this->gameService->addPlayerToGame((int)$data['account_id'], (int)$data['game_id']);
            $player_data = $this->manager->createData(new Item($player, $this->playerTransformer));
            $game = $this->gameService->getState($data['game_id']);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            $games = $this->gameService->getAllGames();
            $games_data = $this->manager->createData(new Collection($games, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_LIST, json_encode($games_data));
            $this->mercureService->publishUpdate(MercureService::GAME_STATE . $game->getId(), json_encode($game_data));
            return $this->json($player_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/remove', name: 'app_player_remove', methods: ['POST'])]
    public function removePlayerFromGame(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('game_id', $data) || !key_exists('account_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id" and "account_id" parameters'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->gameTransformer->setMode(BaseTransformer::FULL_TRANSFORM);
            $game = $this->gameService->removePlayerFromGame((int)$data['account_id'], (int)$data['game_id']);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            $games = $this->gameService->getAllGames();
            $games_data = $this->manager->createData(new Collection($games, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_LIST, json_encode($games_data));
            $this->mercureService->publishUpdate(MercureService::GAME_STATE . $game->getId(), json_encode($game_data));
            return $this->json($game_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/list', name: 'app_player_list', methods: ['GET'])]
    public function getAllPlayers(): Response
    {
        try {
            $players = $this->playerRepository->findAll();
            $players_data = $this->manager->createData(new Collection($players, $this->playerTransformer));
            return $this->json($players_data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
