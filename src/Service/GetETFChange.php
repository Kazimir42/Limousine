<?php

namespace App\Service;


use AlphaVantage\Client;
use AlphaVantage\Options;
use App\Repository\CurrencyChangeRepository;
use App\Repository\ETFRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use App\Service\ExchangeClient\YahooFinanceScrappingClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetETFChange
{
    private $ETFRepository;
    private $client;
    private $entityManager;
    private $rowRepository;
    private $currencyChangeRepository;
    private $totalAccountValue;
    private $security;
    private $investissementRepository;
    private $parameterBag;
    private $yahooFinanceClient;

    //
    /** @var Client $alphaVantageClient */
    private $alphaVantageClient;

    public function __construct(ParameterBagInterface $parameterBag, ETFRepository $ETFRepository, HttpClientInterface $client, EntityManagerInterface $entityManager, RowRepository $rowRepository, CurrencyChangeRepository $currencyChangeRepository, UpdateTotalAccountValue $totalAccountValue, Security $security, InvestissementRepository $investissementRepository)
    {
        $this->parameterBag = $parameterBag;
        $this->ETFRepository = $ETFRepository;
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->rowRepository = $rowRepository;
        $this->currencyChangeRepository = $currencyChangeRepository;
        $this->totalAccountValue = $totalAccountValue;
        $this->security = $security;
        $this->investissementRepository = $investissementRepository;
        //
        $option = new Options();
        $option->setApiKey($parameterBag->get('API_KEY'));
        $this->alphaVantageClient = new Client($option);
        $this->yahooFinanceClient = new YahooFinanceScrappingClient();
    }

    public function getAndPushAllETF()
    {

        $allEtfs = $this->ETFRepository->findOlders();

        $etf = $allEtfs[0];

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();

        $response = $this->yahooFinanceClient->retrieve($etf->getSymbol());
        $value = $response['value'];

        if ($value != null) {
            //$response = $this->alphaVantageClient->timeSeries()->globalQuote($etf->getSymbol());
            //$value = $response["Global Quote"]["05. price"];

            $value = round($value, 2);

            $etf->setValue($value);
            $etf->setUpdatedAt(new \DateTime());
            //PUSH NEW ETF VALUE IN DB
            $this->entityManager->persist($etf);
            $this->entityManager->flush();

            //UPDATE ROWS OF USERS WHO HAVE THIS ETF
            $rows = $this->rowRepository->findBy(array('Symbol' => $etf->getSymbol(), 'type' => 'ETF'));
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

                //update values user with the new etf value
                $this->totalAccountValue->updateTotalValueSpecificUser($user);

            }
        }else{
            $etf->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($etf);
            $this->entityManager->flush();
        }
    }

}