<?php

namespace App\Repository;

use App\Entity\Crypto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Crypto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crypto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crypto[]    findAll()
 * @method Crypto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crypto::class);
    }

    public function searchByName($toSearch){
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->where('c.name LIKE :name');
        $queryBuilder->setParameter('name', $toSearch . '%');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }

    public function updateCryptoUSDBySymbol($crypto)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $query = $queryBuilder->update()
            ->set('c.valueUsd', ':valueUsd')
            ->setParameter('valueUsd', $crypto->getValueUsd())
            ->where('c.symbol = :symbol')
            ->setParameter('symbol', $crypto->getSymbol())
            ->getQuery();
        $query->execute();
    }

    public function updateCryptoEURBySymbol($crypto)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $query = $queryBuilder->update()
            ->set('c.valueEur', ':valueEur')
            ->setParameter('valueEur', $crypto->getValueEur())
            ->where('c.symbol = :symbol')
            ->setParameter('symbol', $crypto->getSymbol())
            ->getQuery();
        $query->execute();
    }

    public function findAllNotNull()
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->where('c.valueUsd > 0');
        $queryBuilder->orWhere('c.valueEur > 0');
        $queryBuilder->orWhere('c.valueBtc > 0');
        $queryBuilder->orWhere('c.valueEth > 0');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }

}
