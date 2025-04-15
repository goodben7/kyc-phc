<?php

namespace App\MessageHandler\Command;

use App\Service\TaskRunner;
use Psr\Log\LoggerInterface;
use App\Repository\TaskRepository;
use App\Message\Command\PendingTaskCommand;
use App\Message\Command\CommandHandlerInterface;

class PendingTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRunner $manager,
        private TaskRepository $repository,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(PendingTaskCommand $event)
    {
        $task = $this->repository->findOneById($event->taskId);

        if($task == null){
            $this->logger->warning('Action not allowed : task not found with Id : ' .$event->taskId);
            return;
        }

        $this->manager->run($task);
    }
}