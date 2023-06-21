<?php

namespace App\Repository;

use App\Entity\Round;
use App\Entity\Subround;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subround>
 *
 * @method Subround|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subround|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subround[]    findAll()
 * @method Subround[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubroundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subround::class);
    }

    public function save(Subround $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subround $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function newSubround(Round $round, int $cards_number): Subround
    {
        $subround = new Subround($round, $cards_number);
        $this->save($subround, true);
        return $subround;
    }

    public function findOneByRound(Round $round): ?Subround
    {
        return $this->findOneBy(["round" => $round], ["cards_number" => "ASC"]);
    }

//    /**
//     * @return Subround[] Returns an array of Subround objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Subround
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
