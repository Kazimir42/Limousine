<?php

namespace App\Repository;

use App\Entity\Row;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Row|null find($id, $lockMode = null, $lockVersion = null)
 * @method Row|null findOneBy(array $criteria, array $orderBy = null)
 * @method Row[]    findAll()
 * @method Row[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Row::class);
    }

    public function findAllByInvestId($investAttach){
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->andWhere('r.investAttach = :investAttach');
        $queryBuilder->setParameter('investAttach', $investAttach);
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }
}
