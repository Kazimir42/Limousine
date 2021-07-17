<?php

namespace App\Service;



use App\Repository\CurrencyChangeRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UpdateEURValues{

    private $investissementRepository;
    private $rowRepository;
    private $security;
    private $currencyChangeRepository;
    private $entityManager;
    private $userRepository;

    public function __construct(InvestissementRepository $investissementRepository, RowRepository $rowRepository, Security $security, CurrencyChangeRepository $currencyChangeRepository,EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->investissementRepository = $investissementRepository;
        $this->rowRepository = $rowRepository;
        $this->security = $security;
        $this->currencyChangeRepository = $currencyChangeRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function UpdateAllEURValuesDB()
    {

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {

            $invests = $this->investissementRepository->findAllByUserId($user);

            foreach ($invests as $invest) {
                $rows = $this->rowRepository->findAllByInvestId($invest->getId());
                foreach ($rows as $row) {
                    $lastTotalRowValue = $row->getTotalValueUSD();
                    if ($row->getDevise() == "EUR") {
                        $row->setValueUSD($row->getValue() * $this->currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue());
                        $row->setTotalValueUSD($row->getValueUSD() * $row->getNumber());

                        $this->entityManager->persist($row);
                        $this->entityManager->flush();

                        $newTotalRowValue = $row->getTotalValueUSD();

                        $difference = $newTotalRowValue - $lastTotalRowValue;

                        $newTotalInvestValue = $invest->getTotalValue() + $difference;
                        $invest->setTotalValue($newTotalInvestValue);

                        $this->entityManager->persist($invest);
                        $this->entityManager->flush();

                    }
                }

            }

        }

    }


}