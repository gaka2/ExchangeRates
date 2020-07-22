<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExchangeRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRate[]    findAll()
 * @method ExchangeRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ExchangeRate::class);
    }

    /**
    * @return ExchangeRate[] Returns an array of ExchangeRate objects
    */
    public function findByCurrencyCode(string $currencyCode, bool $isDescendingOrder) {

        $order = 'ASC';

        if ($isDescendingOrder) {
            $order = 'DESC';
        }

        return $this->createQueryBuilder('e')
            ->andWhere('e.currencyCode = :code')
            ->setParameter('code', $currencyCode)
            ->orderBy('e.date', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCountryCodeAndDate(string $countryCode, string $date): ?ExchangeRate {
        return $this->createQueryBuilder('e')
            ->andWhere('e.currencyCode = :code')
            ->andWhere('e.date = :date')
            ->setParameter('code', $countryCode)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
