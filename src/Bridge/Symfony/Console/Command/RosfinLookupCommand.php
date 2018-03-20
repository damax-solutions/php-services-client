<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Console\Command;

use Damax\Client\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RosfinLookupCommand extends Command
{
    protected static $defaultName = 'rosfin:lookup';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $check = $this->client->checkRosfin(
            $input->getArgument('fullName'),
            $input->getArgument('birthDate')
        );
    }
}
