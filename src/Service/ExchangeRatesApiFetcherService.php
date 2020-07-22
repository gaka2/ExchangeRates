<?php

namespace App\Service;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use App\Entity\ExchangeRate;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

class ExchangeRatesApiFetcherService {

    private $client;
    private $logger;

    private const API_URL = 'http://api.nbp.pl/api/exchangerates/tables/A/last/67/?format=json';
    private const API_URL_FOR_CURRENT_RATES = 'https://api.nbp.pl/api/exchangerates/tables/A/?format=json';

    public function __construct(ClientInterface $client, LoggerInterface $logger) {
        $this->client = $client;
        $this->logger = $logger;
    }

    private function getDataFromExternalApi(string $url) {
        try {
            $request = $this->client->createRequest('GET', $url);
            $response = $this->client->sendRequest($request);

            $dataFromApi = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            return $this->mapDataFromApi($dataFromApi);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            throw new \RuntimeException($e);
        }
    }

    public function getCurrentExchangeRates(): array {
        return $this->getDataFromExternalApi(self::API_URL_FOR_CURRENT_RATES);
    }

    public function getHistoricalExchangeRates(): array {
        return $this->getDataFromExternalApi(self::API_URL);
    }

    private function mapDataFromApi(array $data): array {
        try {
            $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->enableExceptionOnInvalidIndex()->getPropertyAccessor();

            $rates = [];

            foreach ($data as $row) {
                $date = $propertyAccessor->getValue($row, '[effectiveDate]');

                foreach($propertyAccessor->getValue($row, '[rates]') as $exchangeRateData) {
                    $exchangeRate = new ExchangeRate();
                    $exchangeRate->setDate(\DateTime::createFromFormat('Y-m-d', $date));
                    $exchangeRate->setRate($propertyAccessor->getValue($exchangeRateData, '[mid]'));
                    $exchangeRate->setCurrencyCode($propertyAccessor->getValue($exchangeRateData, '[code]'));

                    $rates[] = $exchangeRate;
                }
            }

            return $rates;
        } catch (NoSuchIndexException $e) {
            throw new \InvalidArgumentException('Invalid data passed to ' . __FUNCTION__ . ': ' . var_export($data, true));
        }
    }
}
