<?php

namespace App\Service;

use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UpdateTotalAccountValue{

    private $security;
    private $investissementRepository;
    private $rowRepository;
    private $entityManager;
    private $accountTotalValue;
    private $userRepository;

    public function __construct(Security $security, InvestissementRepository $investissementRepository, RowRepository $rowRepository, EntityManagerInterface $entityManager, GetAccountTotalValue $accountTotalValue, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->investissementRepository = $investissementRepository;
        $this->rowRepository = $rowRepository;
        $this->entityManager = $entityManager;
        $this->accountTotalValue = $accountTotalValue;
        $this->userRepository = $userRepository;

    }

    public function updateTotalValue(){
        $user = $this->security->getUser();
        $invests = $this->investissementRepository->findAllByUserId($user);

        $totalValue = 0;
        $totalValueForInvest = 0;

        foreach ($invests as $invest){
            $totalValueForInvest = 0;
            $rows = $this->rowRepository->findAllByInvestId($invest);

            foreach($rows as $row){
                $totalValue += $row->getTotalValueUSD();
                $totalValueForInvest += $row->getTotalValueUSD();
            }
            $invest->setTotalValue($totalValueForInvest);
            $this->entityManager->persist($invest);
            $this->entityManager->flush();
        }

        $user->setAccountTotalValue($totalValue);


        $this->entityManager->persist($user);
        $this->entityManager->flush();


        //when total value is update for user so save the new total value in table historical from db
        $this->accountTotalValue->getAndPushTotalAccountValueCurrentUser();
    }


    public function updateTotalValueAllUser(){
        $users = $this->userRepository->findAll();

        foreach($users as $user) {

            $invests = $this->investissementRepository->findAllByUserId($user);

            $totalValue = 0;
            $totalValueForInvest = 0;

            foreach ($invests as $invest) {
                $totalValueForInvest = 0;
                $rows = $this->rowRepository->findAllByInvestId($invest);

                foreach ($rows as $row) {
                    $totalValue += $row->getTotalValueUSD();
                    $totalValueForInvest += $row->getTotalValueUSD();
                }
                $invest->setTotalValue($totalValueForInvest);
                $this->entityManager->persist($invest);
                $this->entityManager->flush();
            }

            $user->setAccountTotalValue($totalValue);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            //when total value is update for user so save the new total value in table historical from db
            $this->accountTotalValue->getAndPushTotalAccountValueSpecificUser($user);

        }
    }


}