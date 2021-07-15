<?php

namespace App\Repository;

use App\Entity\Investissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Investissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investissement[]    findAll()
 * @method Investissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investissement::class);
    }

    public function findAllByUserId($userId){
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->andWhere('i.userId = :userId');
        $queryBuilder->setParameter('userId', $userId);
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }


}
