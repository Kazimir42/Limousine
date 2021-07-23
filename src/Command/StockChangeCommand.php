<?php

namespace App\Command;


use App\Service\GetETFChange;
use App\Service\GetStockChange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StockChangeCommand extends Command
{
    private $stockChange;
    protected static $defaultName = 'stock:update';

    public function __construct(GetStockChange $stockChange)
    {
        parent::__construct();
        $this->stockChange = $stockChange;
    }

    protected function configure()
    {
        $this->setDescription('Update older stock');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->stockChange->getAndPushAllStock();
        $output->writeln('older stock as been updated');
        return Command::SUCCESS;
    }

}