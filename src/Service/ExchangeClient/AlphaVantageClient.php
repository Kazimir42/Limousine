<?php


namespace App\Service\ExchangeClient;


class AlphaVantageClient implements ExchangeClient
{

    public function __construct()
    {
    }

    public function retrieve(string $symbol): array
    {
        dd('test');
    }
}