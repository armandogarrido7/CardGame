<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Subround;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function newPlayer(Account $account, Game $game): Player
    {
        $player = new Player($account, $game);
        $this->save($player, true);
        return $player;
    }

    public static function createPlayer(Account $account, Game $game): Player
    {
        return new Player($account, $game);
    }

    public function findSubroundWinner(Subround $subround): Player
    {
        $sql = 'SELECT player.id AS player_id FROM player LEFT JOIN play p on player.id = p.player_id LEFT JOIN card on p.card_id = card.id LEFT JOIN subround s on p.subround_id = s.id WHERE s.id = ? ORDER BY card.number DESC LIMIT 1';
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('player_id', 'id', 'integer');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $subround->getId());
        $player_id = $query->getResult()[0]['id'];
        return $this->find($player_id);
    }

//    /**
//     * @return Player[] Returns an array of Player objects
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

//    public function findOneBySomeField($value): ?Player
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
