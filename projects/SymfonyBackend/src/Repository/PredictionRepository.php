<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Prediction;
use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prediction>
 *
 * @method Prediction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prediction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prediction[]    findAll()
 * @method Prediction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    public function save(Prediction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Prediction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function newPrediction(Player $player, Round $round, bool | int $prediction_content): Prediction
    {
        $prediction = new Prediction($player, $round);
        if (is_int($prediction_content)) {
            $prediction->setRoundsWon($prediction_content);
        } else if (is_bool($prediction_content)) {
            $prediction->setWillWin($prediction_content);
        }
        $this->save($prediction, true);
        return $prediction;
    }

    /**
     * @return Prediction[]
     */
    public function findByRound(Round $round): array
    {
        return $this->findBy(["round" => $round]);
    }

    public function findLastPredictionByPlayer($player): Prediction
    {
        return $this->findOneBy(["player" => $player], ["id" => "DESC"]);
    }
//    /**
//     * @return Prediction[] Returns an array of Prediction objects
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

//    public function findOneBySomeField($value): ?Prediction
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
