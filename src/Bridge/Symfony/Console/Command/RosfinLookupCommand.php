<?php

declare(strict_types=1);

namespace Damax\Services\Client\Bridge\Symfony\Console\Command;

use Damax\Services\Client\Client;
use Damax\Services\Client\InvalidRequestException;
use Damax\Services\Client\RosfinItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RosfinLookupCommand extends Command
{
    protected static $defaultName = 'damax:rosfin:lookup';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setDescription('Lookup rosfin catalogue.')
            ->addArgument('fullName', InputArgument::REQUIRED, 'Full name.')
            ->addArgument('birthDate', InputArgument::OPTIONAL, 'Birth date.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $check = $this->client->checkRosfin(
                $input->getArgument('fullName'),
                $input->getArgument('birthDate')
            );
        } catch (InvalidRequestException $e) {
            $io->error($e->getMessage());

            return 1;
        }

        if (!count($check)) {
            $io->success('Not found.');

            return 0;
        }

        foreach ($check as $item) {
            $io->newLine();
            $io->table(['Field', 'Value'], $this->formatItem($item));
        }
    }

    private function formatItem(RosfinItem $terrorist): array
    {
        return [
            ['ID', $terrorist->id()],
            ['Type', $terrorist->type()],
            ['Full name', implode("\n", $terrorist->fullName())],
            ['Birth date', $terrorist->birthDate() ?: '-'],
            ['Birth place', $terrorist->birthPlace() ?: '-'],
            ['Description', $terrorist->description() ? mb_substr($terrorist->description(), 0, 120) : '-'],
            ['Address', $terrorist->address() ?: '-'],
            ['Resolution', $terrorist->resolution() ?: '-'],
            ['Passport', $terrorist->passport() ?: '-'],
        ];
    }
}
