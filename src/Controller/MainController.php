<?php

namespace App\Controller;

use App\Entity\Historical;
use App\Repository\HistoricalRepository;
use App\Repository\InvestissementRepository;
use App\Service\AccountTotalMath;
use App\Service\GetAccountTotalValue;
use App\Service\GetCryptoChange;
use App\Service\GetETFChange;
use App\Service\GetStockChange;
use App\Service\UpdateEURValues;
use App\Service\UpdateTotalAccountValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(InvestissementRepository $investissementRepository, AccountTotalMath $accountTotalMath, HistoricalRepository $historicalRepository, GetETFChange $ETFChange, GetStockChange $stockChange, GetCryptoChange $cryptoChange): Response{


        //DISPLAY TOTAL VALUE IN USD
        $user = $this->getUser();
        $investissements = $investissementRepository->findAllByUserId($user);
        $bigTotalEur = $accountTotalMath->totalCalcEUR($investissements);

        //GET HISTORICAL DATA FORM THE CHART
        $historys = $historicalRepository->findBy(array('user' => $user));

        $currentDateBefore = new \DateTime('01/01/1900');
        $currentDateBeforeFormated = $currentDateBefore->format('d/m/Y');

        // FOR THE HISTORICAL CHART
        $nawArrayHistorys = [];
        $i = -1;
        foreach($historys as $history){

            $currentDateTime = $history->getDate();
            $currentDate = $currentDateTime->format('d/m/Y');

            if ($currentDateBeforeFormated == $currentDate){
                $nawArrayHistorys[$i] = $history;
            }else{
                $i++;
                $nawArrayHistorys[$i] = $history;
            }
            $currentDateBeforeFormated = $currentDateTime->format('d/m/Y');
        }

        //$ETFChange->getAndPushAllETF();
        //$stockChange->getAndPushAllStock();
        //$cryptoChange->getAndPushAllCrypto();

        return $this->render('main/home.html.twig', [
            "investissements" => $investissements,
            "bigTotalEur" => $bigTotalEur,
            "historys" => $nawArrayHistorys,
        ]);
    }
}
