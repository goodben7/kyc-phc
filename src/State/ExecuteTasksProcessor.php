<?php

namespace App\State;

use Symfony\Component\Uid\Uuid;
use App\ApiResource\ExecuteTask;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Message\Command\CommandBusInterface;
use App\Message\Command\CheckPendingTasksCommand;

class ExecuteTasksProcessor implements ProcessorInterface
{

    public function __construct(private CommandBusInterface $bus)
    {
    }
    
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->bus->dispatch(new CheckPendingTasksCommand());

        return new ExecuteTask(Uuid::v1(), true);
    }
}