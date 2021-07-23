<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function updateStockBySymbol($stock)
    {
        $queryBuilder = $this->createQueryBuilder('s');
            $query = $queryBuilder->update()
                ->set('s.value', ':value')
                ->setParameter('value', $stock->getValue())
                ->where('s.symbol = :symbol')
                ->setParameter('symbol', $stock->getSymbol())
                ->getQuery();
            $query->execute();


    }

    public function findOlders()
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->orderBy('s.updated_at', 'ASC');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();


        return $results;
    }

}
