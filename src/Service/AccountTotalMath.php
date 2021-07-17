<?php

namespace App\Service;

use App\Repository\CurrencyChangeRepository;
use App\Repository\InvestissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class AccountTotalMath extends AbstractController
{

    private $investissements;
    private $security;
    private $currencyChangeRepository;

    public function __construct(InvestissementRepository $investissements, Security $security, CurrencyChangeRepository $currencyChangeRepository)
    {
        $this->investissements = $investissements;
        $this->security = $security;
        $this->currencyChangeRepository = $currencyChangeRepository;
    }


    public function totalCalcUSD($investissements){

        $user = $this->security->getUser();

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();

        $EUR_TO_USD_DIF = $currencyRateValue;

        $totalU = 0;

        foreach ($investissements as $invest){
            $currentDevise = $user->getDevise();
            if ($currentDevise == "EUR"){
                $totalEUR = $invest->getTotalValue(); //le total en EUR de cette investissement
                $totalUSD = $totalEUR * $EUR_TO_USD_DIF;
                $totalU += $totalUSD;
            }else{
                $totalU += $invest->getTotalValue();
            }
        }
        return $totalU;
    }

    public function totalCalcEUR($investissements){

        $user = $this->security->getUser();

        $currencyRateValue = $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'USD', 'currencyTo' => 'EUR'))->getRateValue();

        $USD_TO_EUR_DIF = $currencyRateValue;

        $totalE = 0;

        foreach ($investissements as $invest){
            $currentDevise = $user->getDevise();
            if ($currentDevise == "USD"){
                $totalUSD = $invest->getTotalValue(); //le total en EUR de cette investissement
                $totalEUR = $totalUSD * $USD_TO_EUR_DIF;
                $totalE += $totalEUR;
            }else{
                $totalE += $invest->getTotalValue();
            }
        }
        return $totalE;
    }
}