<?php


namespace App\Service\ExchangeClient;


class AlphaVantageClient implements ExchangeClient
{
    /** @var string $token */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function retrieve(string $symbol): float
    {

    }
}