<?php


namespace App\Service\ExchangeClient;


interface ExchangeClient
{
    public function retrieve(string $symbol): array;
}