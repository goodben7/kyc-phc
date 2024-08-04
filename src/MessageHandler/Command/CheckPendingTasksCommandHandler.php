<?php 

namespace App\MessageHandler\Command;

use Psr\Log\LoggerInterface;
use App\Repository\TaskRepository;
use App\Message\Command\PendingTaskCommand;
use App\Message\Command\CommandBusInterface;
use App\Message\Command\CommandHandlerInterface;
use App\Message\Command\CheckPendingTasksCommand;

class CheckPendingTasksCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepository $repository,
        private LoggerInterface $logger,
        private CommandBusInterface $bus,
    )
    {
    } 
    
    public function __invoke(CheckPendingTasksCommand $event)
    {
        $tasks = $this->repository->getPendingTasks();

        if(count($tasks) > 0){
            $this->logger->info(count($tasks). ' task found for processing');
        }

        foreach ($tasks as $task) {
            $this->bus->dispatch(new PendingTaskCommand($task->getId()));
        }
    }
}