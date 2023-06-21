<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\GameStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    private GameStatusRepository $gameStatusRepository;
    public function __construct(ManagerRegistry $registry, GameStatusRepository $gameStatusRepository)
    {
        parent::__construct($registry, Game::class);
        $this->gameStatusRepository = $gameStatusRepository;
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function newGame(string $name, int $players_num, int $account_id):Game
    {
        $gameStatus = $this->gameStatusRepository->getWaitingPlayers();
        $game = new Game($name, $players_num, $account_id, $gameStatus);
        $this->save($game, true);
        return $game;
    }

    public function findAvailableGames(): array
    {
        $gameStatus = $this->gameStatusRepository->findBy(["name" => GameStatus::WAITING_FOR_PLAYERS]);
        return $this->findBy(["status" => $gameStatus]);
    }
//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
