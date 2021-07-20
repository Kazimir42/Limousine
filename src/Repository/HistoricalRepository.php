<?php

namespace App\Repository;

use App\Entity\Historical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Historical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historical[]    findAll()
 * @method Historical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoricalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historical::class);
    }

    // /**
    //  * @return Historical[] Returns an array of Historical objects
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
    public function findOneBySomeField($value): ?Historical
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
