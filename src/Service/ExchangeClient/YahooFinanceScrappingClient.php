<?php


namespace App\Service\ExchangeClient;



use PHPHtmlParser\Dom;

class YahooFinanceScrappingClient implements ExchangeClient
{


    public function __construct()
    {

    }

    public function retrieve(string $symbol): float
    {
        $dom = new Dom();
        $dom->loadFromUrl('https://fr.finance.yahoo.com/quote/COIN');

        $contents = $dom->find('#app');
        $contents = $contents->find('div data-reactid="1"');
        $contents = $contents->find('div data-reactid="2"');
        $contents = $contents->find('#mrt-node-Lead-4-QuoteHeader');
        $contents = $contents->find('#quote-header-info');
        $contentCurrency = $contents->find('span data-reactid="2"');
        $toDelete = $contents->find('div data-reactid="4"');
        $toDelete->delete();
        unset($toDelete);
        $toDelete2 = $contents->find('div data-reactid="4"');
        $toDelete2->delete();
        unset($toDelete);

        $currencyArray = explode(' ', $contentCurrency->text);
        $currency = end($currencyArray);

        $contents = $contents->find('span data-reactid="31"');
        $value = $contents->text;

        $value  = floatval($value);

        return $value;
    }
}