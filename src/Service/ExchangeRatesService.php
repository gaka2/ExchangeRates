<?php

namespace App\Service;

use App\Service\ExchangeRatesApiFetcherService;
use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExchangeRatesService {

    private $entityManager;
    private $externalApiFetcherService;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, ExchangeRatesApiFetcherService $externalApiFetcherService, ExchangeRateRepository $repository) {
        $this->entityManager = $entityManager;
        $this->externalApiFetcherService = $externalApiFetcherService;
        $this->repository = $repository;
    }

    public function updateCurrentExchangeRates(): void {
        $rates = $this->externalApiFetcherService->getCurrentExchangeRates();
        $this->saveExchangeRates($rates);
    }

    public function updateHistoricalExchangeRates(): void {
        $rates = $this->externalApiFetcherService->getHistoricalExchangeRates();
        $this->saveExchangeRates($rates);
    }

    public function saveExchangeRates(array $rates): void {
        foreach ($rates as $rate) {
            $this->save($rate, false);
        }
        $this->entityManager->flush();
    }

    public function save(ExchangeRate $exchangeRate, bool $callFlush = true): void {

        $existingExchangeRate = $this->repository->findByCountryCodeAndDate($exchangeRate->getCurrencyCode(), $exchangeRate->getDate()->format('Y-m-d'));
        if ($existingExchangeRate === null) {
            $this->entityManager->persist($exchangeRate);
        } else {
            $existingExchangeRate->setRate($exchangeRate->getRate());
        }

        if ($callFlush) {
            $this->entityManager->flush();
        }
    }

    public function getExchangeRatesForCurrency(string $currencyCode, bool $descendingOrder) {
        return $this->repository->findByCurrencyCode($currencyCode, $descendingOrder);
    }

}