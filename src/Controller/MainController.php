<?php

namespace App\Controller;

use App\Repository\InvestissementRepository;
use App\Service\AccountTotalMath;
use App\Service\UpdateEURValues;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(InvestissementRepository $investissementRepository, AccountTotalMath $accountTotalMath, EntityManagerInterface $entityManager, UpdateEURValues $updateEURValues): Response{

        $user = $this->getUser();



        $investissements = $investissementRepository->findAllByUserId($user);

        $totalUSD = $accountTotalMath->totalCalcUSD($investissements);
        $user->setAccountTotalValue($totalUSD);
        $entityManager->persist($user);
        $entityManager->flush();

        $bigTotalEur = $accountTotalMath->totalCalcEUR($investissements);


        return $this->render('main/home.html.twig', [
            "investissements" => $investissements,
            "bigTotalEur" => $bigTotalEur
        ]);
    }
}
