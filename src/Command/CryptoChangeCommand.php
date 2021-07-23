<?php

namespace App\Command;


use App\Service\GetCryptoChange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CryptoChangeCommand extends Command
{
    private $cryptoChange;
    protected static $defaultName = 'crypto:update';

    public function __construct(GetCryptoChange $cryptoChange)
    {
        parent::__construct();
        $this->cryptoChange = $cryptoChange;
    }

    protected function configure()
    {
        $this->setDescription('Update all crypto');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cryptoChange->getAndPushAllCrypto();
        $output->writeln('All Crypto as been updated');
        return Command::SUCCESS;
    }

}