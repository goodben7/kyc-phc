<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Manager\AgentManager;

class CreateAgentProcessor implements ProcessorInterface
{

    public function __construct(private AgentManager $manager)
    {
        
    }

    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->manager->createAgent($data);
    }
}