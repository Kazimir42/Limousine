<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Repository\CryptoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiController extends AbstractController
{
    /**
     * @Route("/call/api/stock/search", name="call_api_stock_symbol_search")
     */
    public function stockSearch(Request $request, HttpClientInterface $client): Response
    {
        $theSymbol = $request->query->get('symbol');

        $response = $client->request(
            'GET',
            'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=' . $theSymbol . '&apikey=' . $this->getParameter('API_KEY')
        //'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=tesco&apikey=demo'
        );

        $data = $response->getContent();

        $data = json_decode($data, true);

        //DELETE ALL ELEMENT WHO ARE NOT IN EUR OR USD CURRENCY OR WHO ARE ETF
        $i = 0;
        foreach ($data["bestMatches"] as $currentData) {
            if ($currentData["8. currency"] == "USD" || $currentData["8. currency"] == "EUR") {
                if ($currentData["3. type"] == "Equity") {

                } else {
                    unset($data["bestMatches"][$i]);
                }
            } else {
                unset($data["bestMatches"][$i]);
            }
            $i++;
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/call/api/stock&etf/price", name="call_api_stock_symbol_price")
     */
    public function stockPrice(Request $request, HttpClientInterface $client): Response
    {
        $theSymbol = $request->query->get('symbol');

        $response = $client->request(
            'GET',
            'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=' . $theSymbol . '&apikey=' . $this->getParameter('API_KEY')
        //'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=IBM&apikey=demo'
        );

        $data = $response->getContent();
        $data = json_decode($data, true);

        return new JsonResponse($data);
    }


    /**
     * @Route("/call/api/etf/search", name="call_api_etf_symbol_search")
     */
    public function ETFSearch(Request $request, HttpClientInterface $client): Response
    {
        $theSymbol = $request->query->get('symbol');

        $response = $client->request(
            'GET',
            'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=' . $theSymbol . '&apikey=' . $this->getParameter('API_KEY')
        //'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=tesco&apikey=demo'
        );

        $data = $response->getContent();

        $data = json_decode($data, true);

        //DELETE ALL ELEMENT WHO ARE NOT IN EUR OR USD CURRENCY OR WHO ARE ETF
        $i = 0;
        foreach ($data["bestMatches"] as $currentData) {
            if ($currentData["8. currency"] == "USD" || $currentData["8. currency"] == "EUR") {
                if ($currentData["3. type"] == "ETF") {

                } else {
                    unset($data["bestMatches"][$i]);
                }
            } else {
                unset($data["bestMatches"][$i]);
            }
            $i++;
        }

        return new JsonResponse($data);
    }



    /**
     * @Route("/call/api/crypto/search", name="call_api_crypto_symbol_search")
     */
    public function cryptoSearch(Request $request, HttpClientInterface $client, CryptoRepository $cryptoRepository, SerializerInterface $serializer): Response
    {
        $theSymbol = $request->query->get('symbol');
        $cryptos = $cryptoRepository->searchByName($theSymbol);
        $data = $serializer->serialize($cryptos, 'json');

        return new JsonResponse($data, 200, [], true);
    }

}