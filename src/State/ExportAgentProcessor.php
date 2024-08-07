<?php

namespace App\State;

use App\Dto\ExportAgentResultDto;
use ApiPlatform\Metadata\Operation;
use App\Service\ExportAgentBuilder;
use ApiPlatform\State\ProcessorInterface;

class ExportAgentProcessor implements ProcessorInterface
{

    public function __construct(private ExportAgentBuilder $manager)
    {   
    }

    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $result = $this->manager->buildAll(); 
        return ExportAgentResultDto::from($result);
    }
}