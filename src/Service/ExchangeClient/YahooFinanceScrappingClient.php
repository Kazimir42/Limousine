<?php


namespace App\Service\ExchangeClient;



use PHPHtmlParser\Dom;

class YahooFinanceScrappingClient implements ExchangeClient
{


    public function __construct()
    {

    }

    public function retrieve(string $symbol): array
    {
        $result = null;

        do
        {

            try {
                $dom = new Dom();
                $dom->loadFromUrl('https://fr.finance.yahoo.com/quote/' . $symbol);

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

                $value = str_replace(' ', '', $value);
                $value = floatval($value);

                $result = [
                    'symbol' => $symbol,
                    'value' => $value,
                    'currency' => $currency
                ];


            }catch(\Exception $exception)
            {


                //change the symbol to got a nice response
                if(strpos($symbol, '.') !== false){
                    $symbolArray = explode('.', $symbol);


                    if (empty($symbolArray[1])){
                        $result = [
                            'symbol' => $symbol,
                            'value' => null,
                            'currency' => null
                        ];
                        return $result;
                    }else{
                        $symbolArray[1] = substr_replace($symbolArray[1] ,"", -1);

                        $symbol = implode('.', $symbolArray);

                        continue;
                    }

                }else{
                    $result = [
                        'symbol' => $symbol,
                        'value' => null,
                        'currency' => null
                    ];

                    return $result;
                }

            }

        }while ($result == null);





        return $result;
    }
}