<?php

namespace App\Repository;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Play;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Subround;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Play>
 *
 * @method Play|null find($id, $lockMode = null, $lockVersion = null)
 * @method Play|null findOneBy(array $criteria, array $orderBy = null)
 * @method Play[]    findAll()
 * @method Play[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Play::class);
    }

    public function save(Play $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Play $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function newPlay(Player $player, Subround $subround, Card $card):Play
    {
        $play = new Play($player, $subround, $card);
        $this->save($play, true);
        return $play;
    }

    public static function createPlay(Player $player, Round $round, Card $card): Play
    {
        return new Play($player, $round, $card);
    }

    /**
     * @return Play[]
     */
    public function findBySubround(Subround $subround): array
    {
        return $this->findBy(["subround" => $subround]);
    }

//    /**
//     * @return Play[] Returns an array of Play objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Play
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
