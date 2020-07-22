<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ExchangeRatesService;

class UpdateHistoricalExchangeRatesCommand extends Command {

    private $exchangeRatesService;

    protected static $defaultName = 'app:update-historical-exchange-rates';

    public function __construct(ExchangeRatesService $exchangeRatesService) {
        $this->exchangeRatesService = $exchangeRatesService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->exchangeRatesService->updateHistoricalExchangeRates();
        return 0;
    }
}