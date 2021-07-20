<?php

namespace App\Service;


use App\Entity\CurrencyChange;
use App\Repository\CurrencyChangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetStockChange
{

    public function __construct()
    {
    }

    public function getAndPushAllStock(){
        /*
         * @todo: recupérer la valeur actuel des stocks que j'ai dans la bdd et les update PUIS update les stocks des users en fonction
         */
    }

}