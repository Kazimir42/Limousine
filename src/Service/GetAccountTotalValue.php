<?php

namespace App\Service;


use App\Entity\CurrencyChange;
use App\Entity\Historical;
use App\Repository\CurrencyChangeRepository;
use App\Repository\HistoricalRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetAccountTotalValue
{

    private $userRepository;
    private $historicalRepository;
    private $entityManager;
    private $security;

    public function __construct(UserRepository $userRepository, HistoricalRepository $historicalRepository, EntityManagerInterface $entityManager, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->historicalRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getAndPushTotalAccountValueAllUsers(){
        $users = $this->userRepository->findAll();

        foreach($users as $user){
            $historicalValueForCurrentUser = new Historical();

            $historicalValueForCurrentUser->setUser($user);
            $historicalValueForCurrentUser->setDate(new \DateTime());

            if ($user->getAccountTotalValue() == 0){
                $historicalValueForCurrentUser->setValue(0);
            }else{
                $historicalValueForCurrentUser->setValue($user->getAccountTotalValue());
            }


            $this->entityManager->persist($historicalValueForCurrentUser);
            $this->entityManager->flush();
        }

    }

    public function getAndPushTotalAccountValueCurrentUser(){
            $user = $this->security->getUser();

            $historicalValueForCurrentUser = new Historical();

            $historicalValueForCurrentUser->setUser($user);
            $historicalValueForCurrentUser->setDate(new \DateTime());

            if ($user->getAccountTotalValue() == 0){
                $historicalValueForCurrentUser->setValue(0);
            }else{
                $historicalValueForCurrentUser->setValue($user->getAccountTotalValue());
            }


            $this->entityManager->persist($historicalValueForCurrentUser);
            $this->entityManager->flush();


    }

    public function getAndPushTotalAccountValueSpecificUser($user){

        $historicalValueForCurrentUser = new Historical();

        $historicalValueForCurrentUser->setUser($user);
        $historicalValueForCurrentUser->setDate(new \DateTime());

        if ($user->getAccountTotalValue() == 0){
            $historicalValueForCurrentUser->setValue(0);
        }else{
            $historicalValueForCurrentUser->setValue($user->getAccountTotalValue());
        }


        $this->entityManager->persist($historicalValueForCurrentUser);
        $this->entityManager->flush();


    }

}