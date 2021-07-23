<?php

namespace App\Service;


use App\Entity\CurrencyChange;
use App\Entity\Investissement;
use App\Entity\User;
use App\Repository\CryptoRepository;
use App\Repository\CurrencyChangeRepository;
use App\Repository\ETFRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCryptoChange
{
    private $cryptoRepository;
    private $client;
    private $entityManager;
    private $rowRepository;
    private $currencyChangeRepository;
    private $totalAccountValue;
    private $investissementRepository;

    public function __construct(CryptoRepository $cryptoRepository, HttpClientInterface $client, EntityManagerInterface $entityManager, RowRepository $rowRepository, CurrencyChangeRepository $currencyChangeRepository, UpdateTotalAccountValue $totalAccountValue, Security $security, InvestissementRepository $investissementRepository)
    {
        $this->cryptoRepository = $cryptoRepository;
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->rowRepository = $rowRepository;
        $this->currencyChangeRepository = $currencyChangeRepository;
        $this->totalAccountValue = $totalAccountValue;
        $this->investissementRepository = $investissementRepository;
    }


    /*
     * @todo: recupérer la valeur actuel des etfs que j'ai dans la bdd et les update PUIS update les stocks des users en fonction
     */
    public function getAndPushAllCrypto(){


        $allCryptos = $this->cryptoRepository->findAllNotNull();

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();


        foreach ($allCryptos as $crypto){
            $response = $this->client->request(
                'GET',
                'https://api.coingecko.com/api/v3/coins/' . $crypto->getNameId()
            );

            $data = $response->getContent();
            $data = json_decode($data, true);

            $values = $data["market_data"]["current_price"];

            $valueUSD = round($values["usd"], 2);
            $valueEUR = round($values["eur"], 2);
            $valueBTC = round($values["btc"], 2);
            $valueETH = round($values["eth"], 2);

            $crypto->setValueUsd($valueUSD);
            $crypto->setValueEur($valueEUR);
            $crypto->setValueBtc($valueBTC);
            $crypto->setValueEth($valueETH);
            $crypto->setUpdatedAt(new \DateTime());

            //PUSH NEW ETF VALUE IN DB
            $this->entityManager->persist($crypto);
            $this->entityManager->flush();


            //UPDATE ROWS OF USERS WHO HAVE THIS ETF
            $rows = $this->rowRepository->findBy(array('Symbol' => $crypto->getSymbol(), 'type' => 'CRYPTO'));
            foreach ($rows as $row){


                //UPDATE VALUE
                if ($row->getDevise() == "USD" || is_null($row->getDevise())){
                    $row->setValue($valueUSD);
                    $row->setTotalValue($valueUSD * $row->getNumber());
                    $row->setValueUSD($valueUSD);
                    $row->setTotalValueUSD($valueUSD * $row->getNumber());
                }elseif ($row->getDevise() == "EUR") {
                    $row->setValue($valueEUR);
                    $row->setTotalValue($valueEUR * $row->getNumber());
                    $row->setValueUSD(round($valueEUR * $currencyRateValue, 2));
                    $row->setTotalValueUSD(round(($valueEUR * $row->getNumber()) * $currencyRateValue, 2));
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

        }

    }

}