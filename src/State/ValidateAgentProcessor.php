<?php

namespace App\State;

use App\Manager\AgentManager;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class ValidateAgentProcessor implements ProcessorInterface
{

    public function __construct(private AgentManager $manager)
    {   
    }
    
    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->manager->validateCustomer($uriVariables['id']);
    }
}