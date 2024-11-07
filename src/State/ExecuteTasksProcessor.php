<?php

namespace App\State;

use Symfony\Component\Uid\Uuid;
use App\ApiResource\ExecuteTask;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Message\Command\CheckPendingTasksCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class ExecuteTasksProcessor implements ProcessorInterface
{

    public function __construct(private MessageBusInterface $bus)
    {
    }
    
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->bus->dispatch(new CheckPendingTasksCommand());

        return new ExecuteTask(Uuid::v1(), true);
    }
}