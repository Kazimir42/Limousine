<?php

namespace App\Repository;

use App\Entity\ETF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ETF|null find($id, $lockMode = null, $lockVersion = null)
 * @method ETF|null findOneBy(array $criteria, array $orderBy = null)
 * @method ETF[]    findAll()
 * @method ETF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ETFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ETF::class);
    }

    public function updateETFBySymbol($etf)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $query = $queryBuilder->update()
            ->set('e.value', ':value')
            ->setParameter('value', $etf->getValue())
            ->where('e.symbol = :symbol')
            ->setParameter('symbol', $etf->getSymbol())
            ->getQuery();
        $query->execute();


    }
}
