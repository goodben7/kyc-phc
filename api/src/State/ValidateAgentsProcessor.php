<?php

namespace App\State;

use App\Manager\AgentManager;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class ValidateAgentsProcessor implements ProcessorInterface
{

    public function __construct(private AgentManager $manager)
    {   
    }
    
    /**
     * @param \App\Dto\ValidateAgentsDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $this->manager->validateAgents($data->agents);
    }
}