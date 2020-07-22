<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ApiService;
use FOS\RestBundle\View\View;

class ExchangeRatesController extends AbstractFOSRestController {

    private $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    /**
     * @Route("/api/exchangerates/current", methods={"GET"})
     */
    public function getCurrentExchangeRates() {
        $exchangeRates = $this->apiService->getCurrentExchangeRates();

        $view = View::create($exchangeRates, Response::HTTP_OK);
        $view->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * @Route("/api/exchangerates/{currencyCode}/{order}", methods={"GET"}, requirements={"order": "desc|asc"}, defaults={"order"="desc"})
     */
    public function getExchangeRatesForCurrency(string $currencyCode, string $order) {

        $isDescendingOrder = $order === 'desc' ? true : false;
        $exchangeRates = $this->apiService->getExchangeRatesForCurrency($currencyCode, $isDescendingOrder);

        $view = View::create($exchangeRates, Response::HTTP_OK);
        $view->setFormat('json');
        return $this->handleView($view);
    }
}
