<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\GameStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameStatus>
 *
 * @method GameStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameStatus[]    findAll()
 * @method GameStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameStatus::class);
    }

    public function save(GameStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GameStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getWaitingPlayers():GameStatus
    {
        return $this->findOneBy(['name' => GameStatus::WAITING_FOR_PLAYERS]);
    }

    public function getWaitingHost():GameStatus
    {
        return $this->findOneBy(['name' => GameStatus::WAITING_HOST_TO_START]);
    }
    public function getStarted():GameStatus
    {
        return $this->findOneBy(['name' => GameStatus::STARTED]);
    }

    public function getFinished():GameStatus
    {
        return $this->findOneBy(['name' => GameStatus::FINISHED]);
    }
//    /**
//     * @return GameStatus[] Returns an array of GameStatus objects
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

//    public function findOneBySomeField($value): ?GameStatus
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
