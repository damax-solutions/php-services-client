<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Console\Command;

use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MvdLookupCommand extends Command
{
    protected static $defaultName = 'damax:mvd:passport:lookup';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setDescription('Lookup invalid passport.')
            ->addArgument('number', InputArgument::REQUIRED, 'Passport number.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $result = $this->client->checkPassport($input->getArgument('number'))->toArray();
        } catch (InvalidRequestException $e) {
            $io->error($e->getMessage());

            return 1;
        }

        $row = function ($value, string $key): array {
            return [$key, is_bool($value) ? ($value ? '+' : '-') : $value];
        };

        $io = new SymfonyStyle($input, $output);
        $io->newLine();
        $io->table(['Field', 'Value'], array_map($row, $result, array_keys($result)));
    }
}
