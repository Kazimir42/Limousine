<?php

namespace App\Service;

use App\Entity\CurrencyChange;
use App\Repository\CurrencyChangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCurrencyChange
{
    private $client;
    private $currencyChange;
    private $entityManager;
    private $totalAccountValue;

    public function __construct(HttpClientInterface $client, CurrencyChangeRepository $currencyChange, EntityManagerInterface $entityManager, UpdateTotalAccountValue $totalAccountValue)
    {
        $this->client = $client;
        $this->currencyChange = $currencyChange;
        $this->entityManager = $entityManager;
        $this->totalAccountValue = $totalAccountValue;
    }

    public function getExchangeRateUSDToEUR(){

        $currentCurrency = new CurrencyChange();

        $response = $this->client->request(
            'GET',
            'https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=USD&to_currency=EUR&apikey='
        );

        $arrayValue = array($response->toArray());

        $currentCurrency->setCurrencyFrom($arrayValue[0]["Realtime Currency Exchange Rate"]["1. From_Currency Code"]);
        $currentCurrency->setCurrencyTo($arrayValue[0]["Realtime Currency Exchange Rate"]["3. To_Currency Code"]);
        $currentCurrency->setRateValue($arrayValue[0]["Realtime Currency Exchange Rate"]["5. Exchange Rate"]);

        $this->currencyChange->updateChangeCurrency($currentCurrency->getCurrencyFrom(), $currentCurrency->getCurrencyTo(), $currentCurrency->getRateValue());
    }

    public function getExchangeRateEURToUSD(){

        $currentCurrency = new CurrencyChange();

        $response = $this->client->request(
            'GET',
            'https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=EUR&to_currency=USD&apikey='
        );

        $arrayValue = array($response->toArray());

        $currentCurrency->setCurrencyFrom($arrayValue[0]["Realtime Currency Exchange Rate"]["1. From_Currency Code"]);
        $currentCurrency->setCurrencyTo($arrayValue[0]["Realtime Currency Exchange Rate"]["3. To_Currency Code"]);
        $currentCurrency->setRateValue($arrayValue[0]["Realtime Currency Exchange Rate"]["5. Exchange Rate"]);

        $this->currencyChange->updateChangeCurrency($currentCurrency->getCurrencyFrom(), $currentCurrency->getCurrencyTo(), $currentCurrency->getRateValue());

    }


}










