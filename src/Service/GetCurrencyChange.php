<?php

namespace App\Service;

use AlphaVantage\Client;
use AlphaVantage\Options;
use App\Repository\CurrencyChangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCurrencyChange
{
    private $client;
    private $currencyChange;
    private $entityManager;
    private $totalAccountValue;
    //
    /** @var Client $alphaVantageClient */
    private $alphaVantageClient;

    public function __construct(ParameterBagInterface $parameterBag, HttpClientInterface $client, CurrencyChangeRepository $currencyChange, EntityManagerInterface $entityManager, UpdateTotalAccountValue $totalAccountValue)
    {
        $this->client = $client;
        $this->currencyChange = $currencyChange;
        $this->entityManager = $entityManager;
        $this->totalAccountValue = $totalAccountValue;
        //
        $option = new Options();
        $option->setApiKey($parameterBag->get('API_KEY'));
        $this->alphaVantageClient = new Client($option);
    }

    public function getExchangeRateUSDToEUR()
    {

        $currentCurrency = $this->currencyChange->findOneBy(array('currencyFrom' => 'USD', 'currencyTo' => 'EUR'));

        $response = $this->alphaVantageClient->foreignExchange()->currencyExchangeRate('USD', 'EUR');

        $currentCurrency->setRateValue($response["Realtime Currency Exchange Rate"]["5. Exchange Rate"]);
        $currentCurrency->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($currentCurrency);
        $this->entityManager->flush();
    }

    public function getExchangeRateEURToUSD()
    {

        $currentCurrency = $this->currencyChange->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'));

        $response = $this->alphaVantageClient->foreignExchange()->currencyExchangeRate('EUR', 'USD');

        $currentCurrency->setRateValue($response["Realtime Currency Exchange Rate"]["5. Exchange Rate"]);
        $currentCurrency->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($currentCurrency);
        $this->entityManager->flush();
    }

}










