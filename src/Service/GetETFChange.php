<?php

namespace App\Service;


use App\Entity\CurrencyChange;
use App\Entity\Investissement;
use App\Entity\User;
use App\Repository\CurrencyChangeRepository;
use App\Repository\ETFRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
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

    public function __construct(ParameterBagInterface $parameterBag,ETFRepository $ETFRepository, HttpClientInterface $client, EntityManagerInterface $entityManager, RowRepository $rowRepository, CurrencyChangeRepository $currencyChangeRepository, UpdateTotalAccountValue $totalAccountValue, Security $security, InvestissementRepository $investissementRepository)
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
    }


    /*
     * @todo: recupérer la valeur actuel des etfs que j'ai dans la bdd et les update PUIS update les stocks des users en fonction
     */
    public function getAndPushAllETF(){

        $allEtfs = $this->ETFRepository->findOlders();

        $etf = $allEtfs[0];

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();

            $response = $this->client->request(
                'GET',
                'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=' . $etf->getSymbol() . '&apikey='. $this->parameterBag->get('API_KEY')
            //'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=tesco&apikey=demo'
            );

            $data = $response->getContent();
            $data = json_decode($data, true);
            $value = $data["Global Quote"]["05. price"];
            $value = round($value, 2);

            $etf->setValue($value);
            $etf->setUpdatedAt(new \DateTime());
            //PUSH NEW ETF VALUE IN DB
            $this->entityManager->persist($etf);
            $this->entityManager->flush();

            //UPDATE ROWS OF USERS WHO HAVE THIS ETF
            $rows = $this->rowRepository->findBy(array('Symbol' => $etf->getSymbol(), 'type' => 'ETF'));
            foreach ($rows as $row){
                $row->setValue($value);
                $row->setTotalValue($value * $row->getNumber());

                if ($row->getDevise() == "USD" || is_null($row->getDevise())){
                    $row->setValueUSD($value);
                    $row->setTotalValueUSD($value * $row->getNumber());
                }elseif ($row->getDevise() == "EUR"){
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
    }

}