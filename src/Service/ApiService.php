<?php

namespace App\Service;

use App\Service\ExchangeRatesApiFetcherService;
use App\Service\ExchangeRatesService;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ApiService {

    private $exchangeRatesService;
    private $externalApiFetcherService;
    private $cache;

    private const CACHE_KEY = 'external_api_response.current_exchange_rates';

    public function __construct(ExchangeRatesService $exchangeRatesService, ExchangeRatesApiFetcherService $externalApiFetcherService, CacheInterface $cache) {
        $this->exchangeRatesService = $exchangeRatesService;
        $this->externalApiFetcherService = $externalApiFetcherService;
        $this->cache = $cache;
    }

    public function getCurrentExchangeRates(): array {

        $exchangeRates = $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(\DateInterval::createFromDateString('1 minute'));

            return $this->externalApiFetcherService->getCurrentExchangeRates();
        });

        return $exchangeRates;
    }

    public function getExchangeRatesForCurrency(string $currencyCode, bool $descendingOrder): array {

        $exchangeRates = $this->exchangeRatesService->getExchangeRatesForCurrency($currencyCode, $descendingOrder);
        return $exchangeRates;
    }
}