<?php

namespace App\Controller;

use App\Service\GameService;
use App\Service\MercureService;
use App\Transformer\BaseTransformer;
use App\Transformer\GameTransformer;
use App\Transformer\PlayTransformer;
use App\Transformer\PredictionTransformer;
use App\Transformer\RoundTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use JsonException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/game')]
class GameController extends AbstractController
{
    private Manager $manager;
    private GameService $gameService;
    private GameTransformer $gameTransformer;
    private MercureService $mercureService;

    public function __construct(GameService $gameService, MercureService $mercureService)
    {
        $this->manager = new Manager();
        $this->gameService = $gameService;
        $this->gameTransformer = new GameTransformer(BaseTransformer::FULL_TRANSFORM);
        $this->mercureService = $mercureService;
    }

    #[Route('/new', name: 'app_game_new', methods: ['POST'])]
    public function newGame(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('name', $data) ||
            !key_exists('players_num', $data) ||
            !key_exists('account_id', $data)
            ) {
            return $this->json(['errorMessage' => 'Request should contain "name", "players_num" and "account_id" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $game = $this->gameService->newGame($data['name'], $data['players_num'], $data['account_id']);
            $games = $this->gameService->getAllGames();
            $games_data = $this->manager->createData(new Collection($games, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_LIST, json_encode($games_data));
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            return $this->json($game_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/list', name: 'app_game_list', methods: ['GET'])]
    public function getAllGames(): Response
    {
        try {
            $games = $this->gameService->getAvailableGames();
            $this->gameTransformer->setMode(BaseTransformer::BASIC);
            $games_data = $this->manager->createData(new Collection($games, $this->gameTransformer));
            return $this->json($games_data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/state', name: 'app_game_state', methods: ['POST'])]
    public function gameState(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('game_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $game = $this->gameService->getState($data['game_id']);
            if (!$game){
                throw new Exception('Game with ID '.$data['game_id'].' Not Found');
            }
            $this->gameTransformer->setMode(BaseTransformer::FULL_TRANSFORM);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            return $this->json($game_data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/delete', name: 'app_game_delete', methods: ['POST'])]
    public function deleteGame(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('game_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->gameService->deleteGame($data['game_id']);
            $games = $this->gameService->getAllGames();
            $this->gameTransformer->setMode(BaseTransformer::FULL_TRANSFORM);
            $games_data = $this->manager->createData(new Collection($games, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_LIST, json_encode($games_data));
            return $this->json(Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/start', name: 'app_game_start', methods: ['POST'])]
    public function startGame(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('game_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $game = $this->gameService->startGame($data['game_id']);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_STATE.$game->getId(), json_encode($game_data));
            return $this->json($game_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/prediction/new', name: 'app_game_prediction_new', methods: ['POST'])]
    public function newPrediction(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('player_id', $data) ||
        !key_exists('prediction', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "player_id" and "prediction" parameter'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $game = $this->gameService->newPrediction($data['player_id'], $data['prediction']);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_STATE.$game->getId(), json_encode($game_data));
            return $this->json($game_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/play/new', name: 'app_game_play_new', methods: ['POST'])]
    public function newPlay(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $this->json(['exceptionMessage' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        if (!key_exists('player_id', $data) ||
            !key_exists('card_id', $data)) {
            return $this->json(['errorMessage' => 'Request should contain "game_id", "player_id" and "card_id" parameters'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $game = $this->gameService->newPlay($data['player_id'], $data['card_id']);
            $game_data = $this->manager->createData(new Item($game, $this->gameTransformer));
            $this->mercureService->publishUpdate(MercureService::GAME_STATE.$game->getId(), json_encode($game_data));
            return $this->json($game_data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['exceptionMessage' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
