<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Round;
use App\Repository\CardRepository;
use App\Repository\GameStatusRepository;
use App\Repository\PlayerRepository;
use App\Repository\GameRepository;
use App\Repository\AccountRepository;
use App\Repository\PlayRepository;
use App\Repository\PredictionRepository;
use App\Repository\RoundRepository;
use App\Entity\Game;
use App\Repository\SubroundRepository;
use App\Transformer\AccountTransformer;
use App\Transformer\BaseTransformer;
use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Doctrine\Common\Collections\ArrayCollection;

class GameService
{
    private GameRepository $gameRepository;
    private GameStatusRepository $gameStatusRepository;
    private AccountRepository $accountRepository;
    private PlayerRepository $playerRepository;
    private PredictionRepository $predictionRepository;
    private RoundRepository $roundRepository;
    private CardRepository $cardRepository;
    private PlayRepository $playRepository;
    private SubroundRepository $subroundRepository;
    private EntityManagerInterface $entityManager;
    private MercureService $mercureService;
    private AccountTransformer $accountTransformer;
    private Manager $manager;

    public function __construct(GameRepository         $gameRepository,
                                GameStatusRepository   $gameStatusRepository,
                                AccountRepository      $accountRepository,
                                PlayerRepository       $playerRepository,
                                PredictionRepository   $predictionRepository,
                                RoundRepository        $roundRepository,
                                PlayRepository         $playRepository,
                                SubroundRepository     $subroundRepository,
                                CardRepository         $cardRepository,
                                EntityManagerInterface $entityManager,
                                MercureService         $mercureService
    )
    {
        $this->gameRepository = $gameRepository;
        $this->gameStatusRepository = $gameStatusRepository;
        $this->accountRepository = $accountRepository;
        $this->playerRepository = $playerRepository;
        $this->predictionRepository = $predictionRepository;
        $this->roundRepository = $roundRepository;
        $this->cardRepository = $cardRepository;
        $this->playRepository = $playRepository;
        $this->subroundRepository = $subroundRepository;
        $this->entityManager = $entityManager;
        $this->mercureService = $mercureService;
        $this->accountTransformer = new AccountTransformer(BaseTransformer::FULL_TRANSFORM);
        $this->manager = new Manager();
    }

    public function newGame(string $name, int $playersNum, int $accountId): Game
    {
        $game = $this->gameRepository->newGame($name, $playersNum, $accountId);
        $account = $this->accountRepository->find($accountId);
        $player = $this->playerRepository->newPlayer($account, $game);
        $this->entityManager->flush();
        $game->addPlayer($player);
        $account->setPlayerId($player->getId());
        $account->setCurrentGame($game->getId());
        $this->entityManager->flush();
        return $game;
    }

    public function getAllGames()
    {
        return $this->gameRepository->findAll();
    }

    public function getAvailableGames(): array
    {
        return $this->gameRepository->findAvailableGames();
    }
    public function addPlayerToGame(int $accountId, int $gameId): ?Player
    {
        $account = $this->accountRepository->find($accountId);
        $game = $this->gameRepository->find($gameId);
        if (!$account) {
            throw new \Exception("There is no account with ID " . $accountId);
        }
        if (!$game) {
            throw new \Exception("There is no game with ID " . $gameId);
        }
        if ($game->getPlayersNumber() < $game->getMaxPlayers()) {
            $account->setCurrentGame($game->getId());
            $player = $this->playerRepository->newPlayer($account, $game);
            $account->setPlayerId($player->getId());
            $game->addPlayer($player);
            if ($game->getPlayersNumber() == $game->getMaxPlayers()) {
                $waitingHostStatus = $this->gameStatusRepository->getWaitingHost();
                $game->setStatus($waitingHostStatus);
            }
            $this->entityManager->flush();
        }
        return $player;
    }

    public function removePlayerFromGame(int $accountId, int $gameId): Game
    {
        $player = $this->playerRepository->findOneBy(["account" => $accountId, "game" => $gameId]);
        $account = $this->accountRepository->find($accountId);
        $game = $this->gameRepository->find($gameId);
        $game->removePlayer($player);
        $waitingPlayersStatus = $this->gameStatusRepository->getWaitingPlayers();
        $game->setStatus($waitingPlayersStatus);
        $account->setCurrentGame(null);
        $this->entityManager->flush();
        return $game;
    }

    public function startGame(int $gameId): Game
    {
        $game = $this->gameRepository->find($gameId);
        $startedStatus = $this->gameStatusRepository->getStarted();
        $game->setStatus($startedStatus);
        $players = $game->getPlayers()->toArray();
        shuffle($players);
        $i = 1;
        foreach ($players as $player){
            $player->setTurnNumber($i);
            $i++;
        }
        $getId = function (Player $player): int {
            return $player->getId();
        };
        $playersIds = array_map($getId, $players);
        $game->setPlayersTurns($playersIds);
        $game->setCurrentPlayer($playersIds[0]);
        $this->dealCards($game);
        $this->entityManager->flush();
        $round = $this->roundRepository->newRound($game);
        $game->addRound($round);
        $this->entityManager->flush();
        return $this->gameRepository->find($game->getId());
    }

    public function dealCards(Game $game): Game
    {
        $cards = $this->cardRepository->findAll();
        shuffle($cards);
        foreach ($game->getPlayers()->getValues() as $player) {
            if ($player->getLives() > 0) {
                $deck = array_slice($cards, 0, $game->getCurrentCardsNumber());
                $cards = array_diff($cards, $deck);
            } else {
                $deck = [];
            }
            $player->setDeck(new ArrayCollection($deck));
        }
        return $game;
    }

    public function getState(int $gameId): Game
    {
        return $this->gameRepository->find($gameId);
    }

    public function deleteGame(int $gameId): void
    {
        $game = $this->gameRepository->find($gameId);
        $players = $game->getPlayers();
        foreach ($players as $player) {
            $account = $player->getAccount();
            $topic = MercureService::UPDATE_ACCOUNT . $account->getId();
            $player->getAccount()->setCurrentGame(null);
            $accountData = $this->manager->createData(new Item($account, $this->accountTransformer));
            $this->mercureService->publishUpdate($topic, json_encode($accountData));
        }
        $this->gameRepository->remove($game, true);
    }

    public function newPrediction(int $playerId, int|bool $predictionContent): Game
    {
        $player = $this->playerRepository->find($playerId);
        $game = $player->getGame();
        $round = $this->roundRepository->findOneByGame($game);
        $prediction = $this->predictionRepository->newPrediction($player, $round, $predictionContent);
        $player->addPrediction($prediction);
        $this->entityManager->flush();
        $currentRoundPredictions = $this->predictionRepository->findByRound($round);
        $this->updateAllowedPredictions($game);
        foreach ($game->getPlayers() as $player){
            foreach ($player->getPlays() as $play){
                $play->setCardFlipped(false);
            }
        }
        $this->entityManager->flush();
        if (count($currentRoundPredictions) === count($game->getPlayersTurns())) {
            if ($game->getCurrentCardsNumber() == 1) {
                $game->setCurrentCardsNumber(5);
                $this->checkOneCardRoundWinners($game);
                $this->checkIfGameFinished($game);
                $currentRoundNumber = $game->getCurrentRoundNumber();
                $currentRoundNumber++;
                $game->setCurrentRoundNumber($currentRoundNumber);
                $round->setFinished(true);
                $round = $this->newRound($game);
                $game->addRound($round);
                $game->setCurrentCardsNumber(5);
                $game->setAllowedPredictions($game->calculateAllowedPredictions());
                $this->updateAllowedPredictions($game);
                $this->dealCards($game);
                $this->passTurn($game);
                $this->entityManager->flush();
            } else {
                $game->setTurnType(Game::PLAY_TURN);
                $subround = $this->subroundRepository->newSubround($round, $game->getCurrentCardsNumber());
                $round->addSubround($subround);
            }

        }
        $this->passTurn($game);
        $this->entityManager->flush();
        return $this->gameRepository->find($game->getId());
    }

    public function newPlay(int $playerId, int $cardId): Game
    {
        $card = $this->cardRepository->find($cardId);
        $player = $this->playerRepository->find($playerId);
        $game = $player->getGame();
        $round = $this->roundRepository->findOneByGame($game);
        $subround = $this->subroundRepository->findOneByRound($round);
        $prevSubround = $this->subroundRepository->find($subround->getId() - 1);
        if ($prevSubround && count($subround->getPlays()) === 0){
            foreach ($prevSubround->getPlays() as $lastPlay){
                $lastPlay->setCardFlipped(false);
            }
        }
        $this->entityManager->flush();
        $play = $this->playRepository->newPlay($player, $subround, $card);
        $subround->addPlay($play);
        $player->addPlay($play);
        $player->removeDeck($card);
        $round->addSubround($subround);
        $this->entityManager->flush();
        if (count($subround->getPlays()) === count($game->getPlayersTurns())) {
            $winner = $this->playerRepository->findSubroundWinner($subround);
            $winner->setRoundsWon($winner->getRoundsWon() + 1);
            $subround->setWinner($winner->getId());
            $game->setCurrentCardsNumber($game->getCurrentCardsNumber() - 1);
            if ($game->getCurrentCardsNumber() >= 1 ) {
                $subround = $this->subroundRepository->newSubround($round, $game->getCurrentCardsNumber());
                $round->addSubround($subround);
            }
            $this->entityManager->flush();
        }
        $currentRoundPlays = 0;
        foreach ($round->getSubrounds() as $subround){
            $currentRoundPlays += count($subround->getPlays());
        }
        if ($currentRoundPlays === $round->getCardsNumber() * count($game->getPlayersTurns())) {
            $this->updatePlayersLives($game);
            $this->checkIfGameFinished($game);
            $game->setTurnType(Game::PREDICTION_TURN);
            if ($round->getCardsNumber() > 1) {
                $game->setCurrentCardsNumber($round->getCardsNumber() - 1);
            } else {
                $game->setCurrentCardsNumber(5);
            }
            $currentRoundNumber = $game->getCurrentRoundNumber();
            $currentRoundNumber++;
            $game->setCurrentRoundNumber($currentRoundNumber);
            $round->setFinished(true);
            $round = $this->newRound($game);
            $game->addRound($round);
            $this->dealCards($game);
            $this->entityManager->flush();
            foreach ($game->getPlayers() as $player) {
                $player->setRoundsWon(0);
            }
        }
        $this->entityManager->flush();
        $game->setAllowedPredictions($game->calculateAllowedPredictions());
        $this->updateAllowedPredictions($game);
        $this->passTurn($game);
        $this->entityManager->flush();
        return $this->gameRepository->find($game->getId());
    }

    public function updateAllowedPredictions(Game $game): void
    {
        if ($game->getCurrentCardsNumber() == 1){
            $allowedPredictions = [true, false];
        } else {
            $allowedPredictions = $game->getAllowedPredictions();
            $round = $this->roundRepository->findOneByGame($game);
            $currentRoundPredictions = $this->predictionRepository->findByRound($round);
            $totalPredictedWins = 0;
            foreach ($currentRoundPredictions as $prediction) {
                $totalPredictedWins += $prediction->getRoundsWon();
            }
            foreach ($allowedPredictions as $key => $number) {
                if ($number + $totalPredictedWins == $game->getCurrentCardsNumber()) {
                    unset($allowedPredictions[$key]);
                }
            }
            sort($allowedPredictions);
        }
        $game->setAllowedPredictions($allowedPredictions);
        $this->entityManager->flush();
    }

    public function newRound(Game $game): Round
    {
        return $this->roundRepository->newRound($game);
    }

    public function passTurn(Game $game): Game
    {
        $newTurns = $game->getPlayersTurns();
        array_push($newTurns, array_shift($newTurns));
        $game->setPlayersTurns($newTurns);
        $game->setCurrentPlayer($newTurns[0]);
        $this->entityManager->flush();
        return $game;
    }

    public function updatePlayersLives(Game $game): void
    {
        foreach ($game->getPlayers() as $player) {
            if ($player->getLives() > 0) {
                $lastPrediction = $this->predictionRepository->findLastPredictionByPlayer($player);
                $roundsWon = $player->getRoundsWon();
                $predictedRoundsWon = $lastPrediction->getRoundsWon();
                $diff = abs($predictedRoundsWon - $roundsWon);
                $lives = $player->getLives();
                $player->setLives($lives - $diff);
                if ($player->getLives() <= 0) {
                    $player->setLives(0);
                    $gamePlayers = $game->getPlayersTurns();
                    $key = array_search($player->getId(), $gamePlayers);
                    unset($gamePlayers[$key]);
                    $game->setPlayersTurns($gamePlayers);
                }
                $this->entityManager->flush();
            }
        }
    }

    public function checkOneCardRoundWinners(Game $game):void
    {
        $playersCardsValue = [];
        foreach ($game->getPlayers() as $player) {
            if ($player->getDeck()){
                $cardsNumber = $player->getDeck()[0]->getNumber();
                $playersCardsValue[$player->getId()] = $cardsNumber;
            }
        }
        arsort($playersCardsValue, SORT_NUMERIC);
        $winnerId = array_key_first($playersCardsValue);
        foreach ($game->getPlayers() as $player) {
            $lastPrediction = $this->predictionRepository->findLastPredictionByPlayer($player);
            $willWin = $lastPrediction->isWillWin();
            if ($willWin && $player->getId() !== $winnerId){
                $player->setLives($player->getLives() - 1);
            }
            else if (!$willWin && $player->getId() === $winnerId){
                $player->setLives($player->getLives() - 1);
            }
            $this->entityManager->flush();
        }
    }

    public function findAlivePlayers(Game $game):array
    {
        $alivePlayers = [];
        foreach ($game->getPlayers()->getValues() as $player){
            if ($player->getLives() > 0){
                $alivePlayers[] = $player;
            }
        }
        return $alivePlayers;
    }
    public function checkIfGameFinished(Game $game):void
    {
        $alivePlayers = $this->findAlivePlayers($game);
        $alivePlayersNum = count($alivePlayers);
        if ($alivePlayersNum === 1){
            $finishedStatus = $this->gameStatusRepository->getFinished();
            $game->setWinner($alivePlayers[0]->getId());
            $alivePlayers[0]->getAccount()->setWins($alivePlayers[0]->getAccount()->getWins() + 1);
            $game->setTurnType(null);
            $game->setStatus($finishedStatus);
            $this->entityManager->flush();
            foreach ($game->getPlayers() as $player) {
                $account = $player->getAccount();
                $account->setCurrentGame(null);
                $account->setGamesPlayed($account->getGamesPlayed() + 1);
                $account->setPlayerId(null);
                $this->entityManager->flush();
            }
        }
    }
}
