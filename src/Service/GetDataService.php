<?php

namespace App\Service;




use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetDataService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getStocks(){


    }




}










