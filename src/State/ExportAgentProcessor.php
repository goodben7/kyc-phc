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
    
        $filters = is_array($data->filters) ? $data->filters : [];

        $result = !empty($filters) ? $this->manager->buildAll($filters) : $this->manager->buildAll();

        return ExportAgentResultDto::from($result);
    }
}