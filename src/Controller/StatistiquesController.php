<?php

namespace App\Controller;

use App\Repository\HistoricalRepository;
use App\Repository\InvestissementRepository;
use App\Service\AccountTotalMath;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/stats", name="stats_")
 */
class StatistiquesController extends AbstractController
{

    /**
     * @Route("", name="home")
     */
    public function index(InvestissementRepository $investissementRepository, AccountTotalMath $accountTotalMath, HistoricalRepository $historicalRepository): Response
    {

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


        $datasRisk = [];
        $datasRisk['Safe'] = 0;
        $datasRisk['Balanced'] = 0;
        $datasRisk['Risky'] = 0;

        foreach($investissements as $invest){
            $risk = $invest->getRisk();
            $totalInvest = $invest->getTotalValue();
            switch ($risk){
                case 'Safe':
                    $datasRisk['Safe'] += $totalInvest;
                    break;
                case 'Balanced':
                    $datasRisk['Balanced'] += $totalInvest;
                    break;
                case 'Risky':
                    $datasRisk['Risky'] += $totalInvest;
                    break;
            }
        }

        return $this->render('statistiques/index.html.twig', [
            "investissements" => $investissements,
            "bigTotalEur" => $bigTotalEur,
            "historys" => $nawArrayHistorys,
            "risks" => $datasRisk,
        ]);
    }
}
