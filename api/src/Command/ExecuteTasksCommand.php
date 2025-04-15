<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use App\Message\Command\CheckPendingTasksCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'phc:execute-task',
    description: 'Execute Tasks',
)]
class ExecuteTasksCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {

            $this->bus->dispatch(new CheckPendingTasksCommand());
            $io->success('Tasks successfully executed');
            return Command::SUCCESS;
        }
        catch(\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}

