<?php

namespace App\State;

use App\Dto\ExportAgentResultDto;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\ExportAgentDocumentBuilder;

class ExportAgentDocumentProcessor implements ProcessorInterface
{

    public function __construct(private ExportAgentDocumentBuilder $manager)
    {   
    }

    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $result =   $this->manager->buildAll();

        return ExportAgentResultDto::from($result);
    }
}