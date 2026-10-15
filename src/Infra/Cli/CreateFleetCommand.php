<?php

declare(strict_types=1);

namespace Fleet\Infra\Cli;

use Fleet\App\Command\CreateFleet;
use Fleet\App\CommandHandler\CreateFleetHandler;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create', description: 'Create a fleet')]
final readonly class CreateFleetCommand
{
    public function __construct(private CreateFleetHandler $handler)
    {
    }

    public function __invoke(OutputInterface $output, #[Argument] string $userId): int
    {
        $output->writeln(($this->handler)(new CreateFleet($userId)));

        return Command::SUCCESS;
    }
}
