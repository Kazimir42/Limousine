<?php

namespace App\Repository;

use App\Entity\HistoricalInvest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistoricalInvest|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoricalInvest|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoricalInvest[]    findAll()
 * @method HistoricalInvest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricalInvestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoricalInvest::class);
    }


    public function findAllForUser(){

    }

    // /**
    //  * @return HistoricalInvest[] Returns an array of HistoricalInvest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoricalInvest
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
