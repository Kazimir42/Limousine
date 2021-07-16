<?php

namespace App\Service;

use App\Repository\InvestissementRepository;

class AccountTotalMath
{

    private $investissements;

    public function __construct(InvestissementRepository $investissements)
    {
        $this->investissements = $investissements;
    }


    public function totalCalcUSD($investissements){
        $EUR_TO_USD_DIF  = 1.18;
        $totalU = 0;
        foreach ($investissements as $invest){
            $currentDevise = $invest->getDevise();
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
        $USD_TO_EUR_DIF  = 0.82;
        $totalE = 0;

        foreach ($investissements as $invest){
            $currentDevise = $invest->getDevise();
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