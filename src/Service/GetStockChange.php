<?php

namespace App\Service;


use AlphaVantage\Client;
use AlphaVantage\Options;
use App\Repository\CurrencyChangeRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use App\Repository\StockRepository;
use App\Service\ExchangeClient\ExchangeClient;
use App\Service\ExchangeClient\YahooFinanceScrappingClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetStockChange
{
    private $stockRepository;
    private $client;
    private $entityManager;
    private $rowRepository;
    private $currencyChangeRepository;
    private $totalAccountValue;
    private $investissementRepository;
    private $parameterBag;
    //
    /** @var Client $alphaVantageClient */
    private $alphaVantageClient;
    private $yahooFinanceClient;


    public function __construct(ParameterBagInterface $parameterBag, StockRepository $stockRepository, HttpClientInterface $client, EntityManagerInterface $entityManager, RowRepository $rowRepository, CurrencyChangeRepository $currencyChangeRepository, UpdateTotalAccountValue $totalAccountValue, InvestissementRepository $investissementRepository)
    {
        $this->parameterBag = $parameterBag;
        $this->stockRepository = $stockRepository;
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->rowRepository = $rowRepository;
        $this->currencyChangeRepository = $currencyChangeRepository;
        $this->totalAccountValue = $totalAccountValue;
        $this->investissementRepository = $investissementRepository;
        //
        $option = new Options();
        $option->setApiKey($parameterBag->get('API_KEY'));
        $this->alphaVantageClient = new Client($option);
        $this->yahooFinanceClient = new YahooFinanceScrappingClient();
    }


    public function getAndPushAllStock()
    {

        $allStocks = $this->stockRepository->findOlders();

        $stock = $allStocks[0];

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();

        $response = $this->yahooFinanceClient->retrieve($stock->getSymbol());
        $value = $response['value'];

        if ($value != null){
            //$response = $this->alphaVantageClient->timeSeries()->globalQuote($stock->getSymbol());
            //$value = $response["Global Quote"]["05. price"];

            $value = round($value, 2);

            $stock->setValue($value);
            $stock->setUpdatedAt(new \DateTime());

            //PUSH NEW ETF VALUE IN DB
            $this->entityManager->persist($stock);
            $this->entityManager->flush();

            //UPDATE ROWS OF USERS WHO HAVE THIS ETF
            $rows = $this->rowRepository->findBy(array('Symbol' => $stock->getSymbol(), 'type' => 'STOCK'));
            foreach ($rows as $row) {
                $row->setValue($value);
                $row->setTotalValue($value * $row->getNumber());

                if ($row->getDevise() == "USD" || is_null($row->getDevise())) {
                    $row->setValueUSD($value);
                    $row->setTotalValueUSD($value * $row->getNumber());
                } elseif ($row->getDevise() == "EUR") {
                    $row->setValueUSD(round($value * $currencyRateValue, 2));
                    $row->setTotalValueUSD(round(($value * $row->getNumber()) * $currencyRateValue, 2));
                }

                $this->entityManager->persist($row);
                $this->entityManager->flush();

                //get user of this rows
                $investId = $row->getInvestAttach();
                $invest = $this->investissementRepository->find($investId);
                $user = $invest->getUser();

                //update values user with the new stock value
                $this->totalAccountValue->updateTotalValueSpecificUser($user);

            }
        }else{
            $stock->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($stock);
            $this->entityManager->flush();
        }



    }
}