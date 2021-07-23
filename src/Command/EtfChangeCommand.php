<?php

namespace App\Command;


use App\Service\GetETFChange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EtfChangeCommand extends Command
{
    private $ETFChange;
    protected static $defaultName = 'etf:update';

    public function __construct(GetETFChange $ETFChange)
    {
        parent::__construct();
        $this->ETFChange = $ETFChange;
    }

    protected function configure()
    {
        $this->setDescription('Update orlder ETF');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->ETFChange->getAndPushAllETF();
        $output->writeln('older etf as been updated');
        return Command::SUCCESS;
    }

}