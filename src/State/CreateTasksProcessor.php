<?php

namespace App\State;

use App\Entity\Task;
use App\Manager\TaskManager;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Message\Command\CommandBusInterface;
use App\Message\Command\CheckPendingTasksCommand;

class CreateTasksProcessor implements ProcessorInterface
{

    public function __construct(
        private TaskManager $manager,
        private EntityManagerInterface $em,
        private CommandBusInterface $bus,)
    {
    }

    /**
     * @param \App\Dto\CreateTaskstDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        foreach($data as $row){
            foreach($row as $value)
            {
                $task = new Task();

                $task->setType($value->type);
                $task->setMethod($value->method);
                $task->setMethod($value->method);
                $task->setData($value->data);
                $task->setCreatedBy($value->createdBy);
                $task->setCreatedAt($value->createdAt);
                $task->setExternalReferenceId($value->externalReferenceId);
                $task->setStatus(Task::STATUS_IDLE);
                $task->setData1($value->data1);
                $task->setData2($value->data2);
                $task->setData3($value->data3);
                $task->setData4($value->data4);
                $task->setData5($value->data5);
                $task->setData6($value->data6);

                $this->em->persist($task);
                
            }   
        }

        $this->em->flush();

        $this->bus->dispatch(new CheckPendingTasksCommand());
        
        return $task;
    }
}