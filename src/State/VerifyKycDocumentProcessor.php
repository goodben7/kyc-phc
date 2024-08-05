<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Manager\AgentManager;

class VerifyKycDocumentProcessor implements ProcessorInterface
{
    public function __construct(private AgentManager $manager)
    {
        
    }

    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->manager->validateKycDocument($uriVariables['id']);
    }
}
