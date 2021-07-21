<?php

namespace App\Command;

use App\Service\GetCurrencyChange;
use App\Service\UpdateEURValues;
use App\Service\UpdateTotalAccountValue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CurrencyChangeCommand extends Command
{
    private $getChangeRateUSDToEUR;
    private $getChangeRateEURToUSD;
    private $updateEURValues;
    private $totalAccountValue;
    protected static $defaultName = 'currency:update';

    public function __construct(GetCurrencyChange $currencyChange, UpdateEURValues $updateEURValues, UpdateTotalAccountValue $totalAccountValue)
    {
        parent::__construct();
        $this->getChangeRateUSDToEUR = $currencyChange;
        $this->getChangeRateEURToUSD = $currencyChange;
        $this->updateEURValues = $updateEURValues;
        $this->totalAccountValue = $totalAccountValue;
    }

    protected function configure()
    {
        $this->setDescription('Update the currency change rate of USD/EUR and EUR/USD and update all needed value in db');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getChangeRateUSDToEUR->getExchangeRateUSDToEUR();
        $this->getChangeRateEURToUSD->getExchangeRateEURToUSD();
        $this->updateEURValues->UpdateAllEURValuesDB();
        $this->totalAccountValue->updateTotalValueAllUser();    //update total value account

        return Command::SUCCESS;
    }

}